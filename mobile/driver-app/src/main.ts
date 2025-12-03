import { BackgroundGeolocation } from '@capawesome/capacitor-background-geolocation';

const API_BASE_URL = (import.meta as any).env?.VITE_API_BASE_URL || 'https://your-production-domain.com';

const statusEl = document.getElementById('status') as HTMLDivElement;
const startBtn = document.getElementById('start') as HTMLButtonElement;
const stopBtn = document.getElementById('stop') as HTMLButtonElement;
const ambulanceIdInput = document.getElementById('ambulanceId') as HTMLInputElement;

let watcherId: string | null = null;

function setStatus(text: string) {
  if (statusEl) statusEl.textContent = text;
}

function getAmbulanceId(): number | null {
  const raw = ambulanceIdInput.value || localStorage.getItem('ambulanceId') || '';
  const n = parseInt(raw, 10);
  return Number.isFinite(n) ? n : null;
}

async function postLocation(ambulanceId: number, latitude: number, longitude: number) {
  try {
    const res = await fetch(`${API_BASE_URL}/update-location`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: ambulanceId, latitude, longitude })
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
  } catch (e) {
    // swallow errors; background geolocation will try again on next sample
  }
}

async function startTracking() {
  const ambId = getAmbulanceId();
  if (!ambId) {
    setStatus('Please enter a valid Ambulance ID');
    ambulanceIdInput.focus();
    return;
  }
  localStorage.setItem('ambulanceId', String(ambId));
  setStatus('Starting background tracking...');

  try {
    watcherId = await BackgroundGeolocation.addWatcher(
      {
        backgroundMessage: 'Tracking location for dispatch',
        backgroundTitle: 'MDRRMO Driver',
        requestPermissions: true,
        stale: false,
        distanceFilter: 0,
        interval: 5000, // 5 seconds
        accuracy: 'high'
      },
      async (position, error) => {
        if (error) {
          setStatus(`BG error: ${error.message || error}`);
          return;
        }
        if (!position || position.latitude == null || position.longitude == null) return;
        setStatus(`Sending ${position.latitude.toFixed(5)}, ${position.longitude.toFixed(5)}`);
        await postLocation(ambId, position.latitude, position.longitude);
        setStatus('Location sent');
      }
    );
    setStatus('Background tracking active');
  } catch (e: any) {
    setStatus(`Failed to start tracking: ${e?.message || e}`);
  }
}

async function stopTracking() {
  try {
    if (watcherId) {
      await BackgroundGeolocation.removeWatcher({ id: watcherId });
      watcherId = null;
    }
    setStatus('Stopped');
  } catch (e: any) {
    setStatus(`Failed to stop: ${e?.message || e}`);
  }
}

// Wire events
startBtn?.addEventListener('click', startTracking);
stopBtn?.addEventListener('click', stopTracking);

// Restore ambulance id on load
ambulanceIdInput.value = localStorage.getItem('ambulanceId') || '';



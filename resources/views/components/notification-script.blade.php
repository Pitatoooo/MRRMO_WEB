<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>

<script>
if (!window.supabaseClient) {
  const SUPABASE_URL = "https://bhcecrbyknorjzkjazxu.supabase.co";
  const SUPABASE_KEY = "YOUR_PUBLIC_KEY";
  window.supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);
}
const supabaseClient = window.supabaseClient;

let unread = 0;

const reportsChannel = supabaseClient
  .channel('public:reports')
  .on(
    'postgres_changes',
    { event: 'INSERT', schema: 'public', table: 'reports' },
    (payload) => {
      const data = payload.new;

      const audio = document.getElementById('globalSound');
      if (audio) audio.play().catch(() => {});

      const toast = document.createElement('div');
      toast.className = 'global-toast';
      toast.innerHTML = `
        <div style="font-size:20px; color:#ef4444;">
          <i class="fas fa-exclamation-circle"></i>
        </div>
        <div>
          <div style="font-weight:800;">New Incident!</div>
          <div style="font-size:13px;">${data.incident_type}</div>
        </div>
      `;
      toast.onclick = () => window.location.href = "{{ route('reported-cases') }}";
      document.getElementById('global-toast-container').appendChild(toast);
      setTimeout(() => toast.remove(), 5000);

      unread++;
      const badge = document.getElementById('notifBadge');
      badge.innerText = unread;
      badge.style.display = 'block';

      const item = document.createElement('div');
      item.className = 'notif-item new';
      item.innerHTML = `
        <div class="icon-box"><i class="fas fa-fire"></i></div>
        <div>
          <div style="font-weight:700;">${data.incident_type}</div>
          <div style="font-size:12px;">${data.location}</div>
        </div>
      `;
      item.onclick = () => window.location.href = "{{ route('reported-cases') }}";

      const list = document.getElementById('notifList');
      if (list.children[0]?.innerText.includes('No notifications')) list.innerHTML = '';
      list.prepend(item);
    }
  )
  .subscribe();

function toggleNotif() {
  document.getElementById('notifPanel').classList.toggle('active');
}

function clearBadge() {
  unread = 0;
  document.getElementById('notifBadge').style.display = 'none';
}
</script>

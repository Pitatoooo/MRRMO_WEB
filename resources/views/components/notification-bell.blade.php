<div class="notif-bell-container">
    <div class="notif-panel" id="notifPanel">
        <div class="notif-header">
            <span>Notifications</span>
            <a href="#" onclick="clearBadge()" style="font-size:12px; color:#3b82f6;">Mark read</a>
        </div>
        <div class="notif-list" id="notifList">
            <div style="padding:20px; text-align:center; color:#94a3b8;">No notifications</div>
        </div>
    </div>

    <div class="notif-bell" onclick="toggleNotif()">
        <i class="fas fa-bell"></i>
        <span class="notif-badge" id="notifBadge">0</span>
    </div>
</div>

<div id="global-toast-container"></div>
<audio id="globalSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

@include('components.notification-script')

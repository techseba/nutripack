<div>
    <div id="clock" class="clock text-sm text-slate-700 font-medium"></div>

    <script>
        function updateTime() {
            const now = new Date();
            // Formats to local time string (e.g., 10:30:00 AM)
            const timeString = now.toLocaleTimeString();
            // Formats to local date string (e.g., 4/6/2026)
            const dateString = now.toLocaleDateString();

            // document.getElementById('clock').innerHTML = dateString + ' - ' + timeString;
            document.getElementById('clock').innerHTML = timeString;
        }

        // Update the time every 1 second (1000 milliseconds)
        setInterval(updateTime, 1000);

        // Initial call to display time immediately
        updateTime();
    </script>
</div>

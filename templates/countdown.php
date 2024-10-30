<link href="<?= plugins_url("../css/style.css", __FILE__); ?>" rel="stylesheet" type="text/css" />

<script>
   jQuery(document).ready(function($) {
      $.fn.countdown = function(duration) {
         
         // Get reference to container, and set initial content
         time = counter(duration);
         var container = $(this[0]).html('<span id="bdc_days">' + time.days + '</span><span id="bdc_hours">' + time.hours + '</span><span id="bdc_minutes">' + time.minutes + '</span><span id="bdc_seconds">' + time.seconds + '</span>');
         // Get reference to the interval doing the countdown
         var countdown = setInterval(function() {
            // If seconds remain
            if (--duration) {
               // Update our container's message
               time = counter(duration);
               
               container.html('<span id="bdc_days">' + time.days + '</span><span id="bdc_hours">' + time.hours + '</span><span id="bdc_minutes">' + time.minutes + '</span><span id="bdc_seconds">' + time.seconds + '</span>');
               // Otherwise
            } else {
               // Clear the countdown interval
               clearInterval(countdown);
               
               window.location = "<?= $redirect[0]; ?>";
            }
            // Run interval every 1000ms (1 second)
         }, 1000);
      };

      $("#bdc").countdown(<?= strtotime($date[0] . ' ' . $time[0]) - time(); ?>);
   });

   function counter(seconds) {
      days = Math.floor(seconds / 86400)
      seconds_rest = seconds - days * 86400
      hours = Math.floor(seconds_rest / 3600)
      seconds_rest = seconds - days * 86400 - hours * 3600
      minutes = Math.floor(seconds_rest / 60)
      secondi_resto = seconds - days * 86400 - hours * 3600 - minutes * 60

      return {
         days: days,
         hours: hours,
         minutes: minutes,
         seconds: secondi_resto
      }
   }
</script>

<div id="bdc"></div>



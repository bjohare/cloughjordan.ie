jQuery(function($) {
    var $startButton = $('.wpbdp-page-manual-upgrade a.start-upgrade');
    var $pauseButton = $('.wpbdp-page-manual-upgrade a.pause-upgrade');
    var $progressArea = $('textarea#manual-upgrade-progress');
    var inProgress = false;

    var makeProgress = function() {
        if (!inProgress)
            return;

        var data = { action: 'wpbdp-manual-upgrade' };
        $.get(ajaxurl, data, function(response) {
            var currentText = $progressArea.val();
            var newLine = (response.ok ? "*" : "!") + " " + response.status;

            $progressArea.val(currentText + newLine + "\n");
            $progressArea.scrollTop($progressArea[0].scrollHeight - $progressArea.height());

            if (response.done) {
                $( 'div.step-upgrade' ).fadeOut(function() { $('div.step-done').fadeIn() });
            } else {
                makeProgress();
            }
        }, 'json');
    };
    
    $startButton.click(function(e) {
        e.preventDefault();

        if (inProgress)
            return;

        inProgress = true;
        makeProgress();
    });

    $pauseButton.click(function(e) {
        e.preventDefault();
        inProgress = false;
    });

});
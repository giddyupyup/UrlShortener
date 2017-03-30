(function(){
    
    function loadThis() {
        var clips = new Clipboard("#copytoclip");

        $("#done_btn").click( function() {
            $("#background").remove();
            $("#show_shortcode").remove();
        });
    }
    
    window.addEventListener("load", loadThis);
    
})();
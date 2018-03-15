</div>
<div class="footer">
    <div class="container">
        <div class="agile-footer">
            <div class="agileinfo-social-grids">
                <ul>
                    <li><a href="https://nl-nl.facebook.com/Hydrofiel"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="https://www.snapchat.com/add/hydrofiel"><i class="fa fa-snapchat-ghost"></i></a></li>
                    <li><a href="https://www.instagram.com/nszwvhydrofiel/"><i class="fa fa-instagram"></i></a></li>
                    <li><a href="mailto:bestuur@hydrofiel.nl"><i class="fa fa-envelope"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>Copyright © <?php echo date('Y'); ?> N.S.Z.&W.V. Hydrofiel | <a style="color: white                                                                                                                             " href="https://www.bintzandt.nl/">Design by Bram in 't Zandt</a></p>
        </div>
    </div>
    <!--  SummerNote  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.10/summernote.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.10/summernote.css" />

    <script src="/js/bootstrap-model-wrapper.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(function() {
                $('#summernote').summernote({
                    // unfortunately you can only rewrite
                    // all the toolbar contents, on the bright side
                    // you can place uploadcare button wherever you want
                    height: 350,
                    toolbar: [
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'hr']],
                        ['view', ['fullscreen', 'codeview']],
                        ['help', ['help']]
                    ]
                });
            });
            $(function() {
                $('#engels').summernote({
                    // unfortunately you can only rewrite
                    // all the toolbar contents, on the bright side
                    // you can place uploadcare button wherever you want
                    height: 350,
                    toolbar: [
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'hr']],
                        ['view', ['fullscreen', 'codeview']],
                        ['help', ['help']]
                    ]
                });
            });

            $(".clickable-row").click(function() {
                window.location = $(this).data("href");
            });
        });
    </script>
    <!--    datepicker-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js" async></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" />

    <!--    multiselect-->
    <script type="text/javascript" src="/js/bootstrap-multiselect.min.js"></script>
    <link href="https://opensource.keycdn.com/fontawesome/4.7.0/font-awesome.min.css" rel="stylesheet">

    <!-- small script for getting data from facebook -->
    <script>
        function getImage(id){
            var url = "https://graph.facebook.com/";
            var access_token = "/attachments?access_token=1787347374631170|MVXgVmEVfr8FjeOrCV_M-fP68Ys";
            $.getJSON(url + id + access_token, function (json) {
                if (json.data.length > 0) {
                    $.each(json.data, function (i, media){
                        if (media.media !== undefined) {
                            $('#' + id).html("<img class='img-responsive no_margin' src='" + media.media.image.src + "'>");
                        }
                        else if (media.subattachments !== undefined) {
                            $('#' + id).html("<img class='img-responsive no_margin' src='" + media.subattachments.data[0].media.image.src + "'>");
                        }
                    });
                }
                else {
                    //console.debug(id);
                    $('#' + id).remove();
                    $('#txt_' + id).toggleClass('col-md-9 col-sm-12');
                }
            });
        }

        if ($('#facebookfeed').length > 0) {
            // URL to access
            var url = "https://graph.facebook.com/hydrofiel/feed?access_token=1787347374631170|MVXgVmEVfr8FjeOrCV_M-fP68Ys&limit=7&fields=id,message&callback=?";
            // JSON request to get the data
            $.getJSON(url, function (json) {
                var html = "";
                //Add some formatting to the message
                $.each(json.data, function (i, fb) {
                    if (fb.message != null) {
                        html += "<div class='container container-item'>" +
                                        "<div class='col-md-3' id='" + fb.id + "'></div>" +
                                    "<div class='col-md-9' id='txt_" + fb.id + "' align='left'>" +
                                        "<p>" +
                                            $('<div>').html(fb.message).text() +
                                        "</p>" +
                                    "</div>" +
                                "</div>" +
                                "<hr>";
                        getImage(fb.id);
                    }
                });
                //Remove the last <hr>
                html = html.slice(0, -4);
                $('#facebookfeed').html(html);
            });
        }
        $(function(){
            $("h1").each(function(){
                $(this).addClass("oranje_tekst");
            });
            $("h3").each(function(){
                $(this).addClass("oranje_tekst");
            });
            $("h4").each(function(){
                $(this).addClass("oranje_tekst");
            });
            $("img").each(function(){
                $(this).addClass("img-responsive");
            });
        });
    </script>

</div>
</body>
</html>
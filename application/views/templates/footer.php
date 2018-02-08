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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js" async></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">

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
    <script type="text/javascript" src="js/bootstrap-multiselect.min.js"></script>
    <link href="https://opensource.keycdn.com/fontawesome/4.7.0/font-awesome.min.css" rel="stylesheet">
</div>
</body>
</html>
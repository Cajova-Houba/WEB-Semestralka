<?php
/*
 * Page footer.
 */


class FooterView {

    static function getHTML() {
        return "
                    <div class=\"row\">
                        <div class=\"col-xs-6 col-md-4\"></div>
                        <div class=\"col-xs-6 col-md-4\">
                            <footer>
                                <p>&copy; 2016 Zdeněk Valeš</p>
                            </footer>
                        </div>
                    </div>
                </div> <!-- main container end -->
            </body>
        ";
    }
}
?>

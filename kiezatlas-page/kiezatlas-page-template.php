<?php
/**
 * The Template for displaying one configured city map page.
 * Copy the contents surrounding the content-area (to be found in your themes single.php-template-file) into this kiezatlas-page-template.php.
 *
 * About this file: 
 * This template-page loads a complete city map from kiezatlas.de (via http-proxies) and 
 * renders its content as an interactive overview (html/js-style) as a wordpress page.
 *
 * @package Kiezatlas-Pages
 * @version 1.0-SNAPSHOT
 */

get_header(); ?>

<div class="layout-wrapper">
    <div class="content-layout">
        <div class="content-layout-row">
            <div class="layout-cell content">

                <?php get_sidebar('top');  ?>

                <!-- Kiezatlas Template Body Begins -->

                <div id="content" class="kiezatlas citymap page" role="main">

                <?php
                    $content = $post->post_content;
                    // 
                    $workspaceId = -1;
                    $cityMapId = -1;
                    $custom_values = get_post_custom_values("Kiezatlas Citymap ID", $post->ID);
                    if (count($custom_values) > 0) {
                        $cityMapId = $custom_values[0];
                         // echo "cityMapId is: ".$cityMapId;
                    }
                    // 
                    $workspaceId = -1;
                    $custom_values = get_post_custom_values("Kiezatlas Workspace ID", $post->ID);
                    if (count($custom_values) > 0) {
                        $workspaceId = $custom_values[0];
                        // echo "workspaceId is: ".$workspaceId;
                    }
                    // 
                    echo "Lade Stadtplaninformationen .. ";
                ?>

                    <script type="text/javascript">
                        var cityMapEhrenamtId = "<?php echo $cityMapId ?>";
                        var workspaceEhrenamtId = "<?php echo $workspaceId ?>";

                        var pluginBase = "<?php echo plugins_url('kiezatlas-page') ?>";
                        kiezatlas.setServiceUrl(pluginBase);
                        // console.log("setServiceUrl => " + kiezatlas.serviceUrl);
                        kiezatlas.setIconsFolder("http://www.kiezatlas.de/client/icons/");
                        kiezatlas.setImagesFolder("http://www.kiezatlas.de/client/images/");

                        kiezatlas.loadWorkspaceCriterias(cityMapEhrenamtId, function () {
                            renderOverview()
                            // start loading the map
                            loadCityMap()
                        })

                        function renderOverview () {
                            var $content = jQuery('<div class="criterias">')
                                /** $content.append('<i>Hinweis: Dies ist eine <b>beispielhafte</b> Integration eines beliebigen '
                                    + ' <a href="http://www.kiezatlas.de" title="zur Kiezatlas Startseite">Kiezatlas</a> '
                                    + ' Stadtplans in Form eines Wordpress-Plugins zu Demonstrationszwecken f&uuml;r '
                                    + 'die Berliner Familiennacht.</i>'
                                    + '<p>&nbsp;</p><hr><b>Bitte w&auml;hlen Sie eine Kategorie aus.</b>') **/
                            var $search_container = jQuery('<div class="search-area">')
                                $search_container.append('<label for="Suche nach: Name oder Stichwort">Suche nach: Name oder Stichwort</label><br/>')
                            var $input_field = jQuery('<input type="text" id=c" class="query-input" placeholder="Suche nach: Name oder Stichwort"></input><br>')
                                // $input_field.clic
                            var $ok_button = jQuery('<input type="submit" class="do-search" value="Suchen"></input>')

                                $ok_button.click(function (e) {

                                    triggerSearch()

                                })

                                function triggerSearch () {
                                    // get searched-value
                                    var query_string = jQuery('.query-input').val()
                                    // fire search
                                    kiezatlas.searchRequest(query_string, function (response) {
                                        // 
                                        jQuery('.kiezatlas').html('<a class="back-button" title="zur&uuml;ck zur '
                                            + 'Programm-&Uuml;bersicht" href="javascript:renderOverview()">'
                                            + 'zur&uuml;ck zur Programm-&Uuml;bersicht</a>')
                                        $content = jQuery('<div class="category-list">')
                                        renderResults($content, kiezatlas.searchResults)

                                        jQuery('.kiezatlas').append('<span class="title">'
                                            + kiezatlas.searchResults.length + ' Veranstaltung unter \"' +query_string+ '\"</span>')
                                        jQuery('.kiezatlas').append($content)
                                        //
                                    })
                                }

                                $search_container.append($input_field).append($ok_button)
                                $content.append($search_container)
                            var page = "";
                            for (i=0; i < kiezatlas.workspaceCriterias.result.length; i++) {
                                var crit = kiezatlas.workspaceCriterias.result[i]
                                var categories = crit.categories
                                $criteria = jQuery('<div class="criteria">')
                                $criteria.append('<span class="title">' + crit.critName + '</span>')
                                $cats = jQuery('<div class="categories">')
                                for (k=0; k < categories.length; k++) {
                                    var category = categories[k]
                                    $button = jQuery('<a id="' +category.catId+ '"'
                                        + ' class="' +crit.critId+ '">' +category.catName+ '</a>')
                                    $button.click(function(e) {
                                        kiezatlas.setSelectedCriteria(e.currentTarget.className)
                                        showCategory(e.target.id)
                                    })
                                    $cats.append($button)
                                }
                                $criteria.append($cats)
                                $content.append($criteria)
                            }
                            jQuery('.kiezatlas').html($content)
                        }

                        function loadCityMap (categoryId) {
                            kiezatlas.loadCityMapTopics(cityMapEhrenamtId, workspaceEhrenamtId, function() {
                                if (categoryId != undefined) {
                                    showCategory(categoryId)
                                }
                            });
                        }

                        function showCategory(catId, critId) {
                            if (kiezatlas.getMapTopics() == undefined) {
                                loadCityMap(catId)
                            } else {

                                var resultset = kiezatlas.getAllTopicsInCat(catId)
                                var category = kiezatlas.getCategory(catId)

                                jQuery('.kiezatlas').html('<a class="back-button" title="zur&uuml;ck zur '
                                    + 'Programm-&Uuml;bersicht" href="javascript:renderOverview()">'
                                    + 'zur&uuml;ck zur Programm-&Uuml;bersicht</a>')

                                $content = jQuery('<div class="category-list">')
                                renderResults($content, resultset)

                                jQuery('.kiezatlas').append('<span class="title">'
                                    + resultset.length + ' Veranstaltung unter <br/>\"' +category.name+ '\"</span>')
                                jQuery('.kiezatlas').append($content)
                            }
                        }

                        function renderResults($container, results) {
                            $listing = jQuery('<ol class="results">')
                            for (m=0; m < results.length; m++) {
                                $item = jQuery('<li><a title="zu weiteren Informationen" href="/kiezatlas-entry/page/?topicId='
                                    + results[m].id+'">' +results[m].name+ '</a></li>')
                                $listing.append($item)
                            }
                            $container.append($listing)
                        }

                    </script>

                </div><!-- #content -->

                <!-- Kiezatlas Template Body Ends -->

                <?php get_sidebar('bottom'); ?> 
                <div class="cleared"></div>
            </div>

            <div class="layout-cell sidebar1">
                <?php get_sidebar('default'); ?>
                <div class="cleared"></div>
            </div>

        </div>
    </div>
</div>
<div class="cleared"></div>


<?php get_footer(); ?>

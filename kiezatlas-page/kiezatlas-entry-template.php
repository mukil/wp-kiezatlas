<?php
/**
 *
 * The Template for displaying one kiezatlas entry per page.
 * Copy the contents surrounding the content-area (to be found in your themes single.php-template-file) into 
 * this XYZ-template.php to adapt this plugin to your theme.
 * 
 *
 * About this file: 
 * This template-page loads a single data-entry from kiezatlas.de (via http-proxies) and 
 * renders its data as a simple kiezatlas-wordpress page.
 * 
 * @package Kiezatlas-Pages
 * @version 1.0-SNAPSHOT
 */

$topic = array();

get_header(); ?>

<div class="layout-wrapper">
    <div class="content-layout">
        <div class="content-layout-row">
            <div class="layout-cell content">


                <?php

                    $topicId = -1;

                    if (isset($wp_query->query_vars['topicId'])) {
                        $topicId = $wp_query->query_vars['topicId'];
                    }

                ?>

                <?php
                    if ($topicId != -1) {
                        require_once("proxies/HTTP/Request.php");
                        // $request = new HTTP_Request('http://localhost:8080/kiezatlas/rpc/');
                        $request = new HTTP_Request('http://www.kiezatlas.de:8080/rpc/');
                        $body = '{"method": "getGeoObjectInfo", "params": ["'.$topicId.'"]}';
                        $request->addHeader("Content-Type", "application/json");
                        $request->setBody($body);
                        $request->setMethod(HTTP_REQUEST_METHOD_POST);
                        $request->sendRequest();
                        $geoObjectInfo = $request->getResponseBody();
                        $geoObjectInfo = utf8_encode($geoObjectInfo);
                        $topic = json_decode($geoObjectInfo, TRUE);
                        $topic = $topic['result'];
                        $title = $topic['name'];
                        // get geo coordinates from kiezatlas
                        $lat = 0;
                        $lng = 0;
                        foreach ($topic['properties'] as &$property ) {
                            if ($property['name'] == "LONG") {
                                //
                                $lng = $property['value'];
                            } else if ($property['name'] == "LAT") {
                                //
                                $lat = $property['value'];
                            }
                        }
                ?>

                <?php get_sidebar('top');  ?>

                <!-- Kiezatlas Template Body Begins -->

                <div id="content" class="kiezatlas entry" style="display: inline-block;" role="main">

                    <div id="entry">

                        <h1 class="postheader"><?php echo $title; ?></h1>

                        <a title="zur&uuml;ck zur &Uuml;bersicht" class="back-button" href="/kiezatlas-citymap/stadtplan/">zur&uuml;ck zur &Uuml;bersicht</a>

                        <?php echo $content; ?><br/>
                        <?php
                            foreach ($topic['properties'] as &$property ) {
                                if ($property['type'] == "1") { // Multi-Fields
                                    // echo $property['name'];
                                } else { // Single-Fields
                                    if ($property['name'] == "Address / Street") {
                                        echo '<b class="label">Straße, Hausnummer:</b>&nbsp;'.$property['value'].', ';
                                    } else if ($property['name'] == "Address / Postal Code") {
                                        echo $property['value'].' Berlin<br/><br/>';
                                    } else if ($property['name'] == "Veranstaltungsort") {
                                        echo '<b class="label">Veranstaltungsort:</b>&nbsp;'.$property['value'].'<br/><br/>';
                                    } else if ($property['name'] == "Weitere Hinweise") {
                                        echo '<b class="label">Weitere Hinweise:</b>&nbsp;'.$property['value'].'<br/><br/>';
                                    } else if ($property['name'] == "Angebotsbeschreibung") {
                                        echo '<b class="label">Angebotsbeschreibung:</b>&nbsp;'.$property['value'].'<br/><br/>';
                                    } else if ($property['name'] == "Preis für Kinder pro Person") {
                                        echo '<b class="label">Preis für Kinder pro Person:</b>&nbsp;'.$property['value'].'<br/><br/>';
                                    } else if ($property['name'] == "Preis für Erwachsene pro Person") {
                                        echo '<b class="label">Preis für Erwachsene pro Person:</b>&nbsp;'.$property['value'].'<br/><br/>';
                                    } else if ($property['name'] == "Ermäßigung mit Familienpass") {
                                        echo '<b class="label">Ermäßigung mit Familienpass:</b>&nbsp;'.$property['value'].'<br/><br/>';
                                    } else if ($property['name'] == "Anmeldung") {
                                        echo '<b class="label">Anmeldung:</b>&nbsp;'.$property['value'].'<br/><br/>';
                                    } else if ($property['name'] == "Anmeldung unter") {
                                        echo '<b class="label">Anmeldung unter:</b>&nbsp;'.$property['value'].'<br/><br/>';
                                    } else if ($property['name'] == "Zeit") {
                                        echo '<b class="label">Zeit:</b>&nbsp;'.$property['value'].'<br/><br/>';
                                    } else if ($property['name'] == "Stichworte") {
                                        echo '<b class="label">Stichworte:</b>&nbsp;'.$property['value'].'<br/><br/>';
                                    } else if ($property['name'] == "Veranstalter") {
                                        echo '<b class="label">Veranstalter:</b>&nbsp;'.$property['value'].'<br/><br/>';
                                    } else if ($property['name'] == "Barrierearmut") {
                                        echo '<b class="label">Barrierearmut:</b>&nbsp;'.$property['value'].'<br/><br/>';
                                    } else if ($property['name'] == "Akteur Logo") {
                                        $akteurLogo = $property['value'];
                                        if (!empty($akteurLogo)) {
                                            if (!strpos($akteurLogo, '.pdf') && !strpos($akteurLogo, '.psd') && !strpos($akteurLogo, '.tif') && 
                                                !strpos($akteurLogo, '.eps')) {
                                                echo '<img src="http://www.kiezatlas.de/client/images/'.$akteurLogo.'"/><br/><br/>';
                                            } else {
                                                // echo "PDF-File Akteur...: ".strpos($akteurLogo, '.pdf');
                                            }
                                        }
                                    } else if ($property['name'] == "Image / File") {
                                        $imgPath = $property['value'];
                                        if (!empty($imgPath)) {
                                            echo '<img class="bild" src="http://www.kiezatlas.de/client/images/'.$imgPath.'"/><br/><br/>';
                                        }
                                    } else {
                                        // echo $property['name'] .'<br/>';
                                    }
                                }
                            }
                        ?>
                        <br/>
                    </div>

                    <h3>Veranstaltungsort - Kartenansicht</h3><br/>
                    <div id="map" style="height: 400px; width: 100%;"></div>

                    <script type="text/javascript">
                        // console.log("Lat: " + <?php echo $lat; ?>)
                        // console.log("Lng: " + <?php echo $lng; ?>)
                        // create a map in the "map" div, set the view to a given place and zoom
                        var map = L.map('map').setView([<?php echo $lat; ?>, <?php echo $lng; ?>], 13);
                        // add an OpenStreetMap tile layer
                        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);
                        // add a marker in the given location, attach some popup content to it and open the popup
                        L.marker([<?php echo $lat; ?>, <?php echo $lng; ?>]).addTo(map)
                            .bindPopup("<?php echo $topic['name']; ?>")
                            .openPopup();
                    </script>

                    <?php
                        } else {
                            echo "<br/><br/><p><small>Currently you have to append a valid "
                                ."\"Kiezatlas Topic ID\" as a URL-Parameter (append \"?topicId=t-123\" to "
                                ." this URL).</small></p>";
                        }
                    ?>

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

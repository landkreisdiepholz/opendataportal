<?php

$debug = 0;
if($debug == 1)
    echo "DEBUG ON!";

    ini_set('display_errors',$debug);

$basedir = dirname(__FILE__);
include($basedir."/functions/_loader.php");
$page = new page();
if(!$page->is_api)
{
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
    "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html lang="de" dir="ltr"
      xmlns:content="http://purl.org/rss/1.0/modules/content/"
      xmlns:dc="http://purl.org/dc/terms/"
      xmlns:foaf="http://xmlns.com/foaf/0.1/"
      xmlns:og="http://ogp.me/ns#"
      xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
      xmlns:sioc="http://rdfs.org/sioc/ns#"
      xmlns:sioct="http://rdfs.org/sioc/types#"
      xmlns:skos="http://www.w3.org/2004/02/skos/core#"
      xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
      xmlns:owl="http://www.w3.org/2002/07/owl#"
      xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
      xmlns:rss="http://purl.org/rss/1.0/"
      xmlns:site="http://172.30.10.113/ns#"
      xmlns:dcat="http://www.w3.org/ns/dcat#">
<head profile="http://www.w3.org/1999/xhtml/vocab">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/vnd.microsoft.icon" />
    <title>OpenData Diepholz</title>
    <style type="text/css" media="all">
        @import url("/css/system.base.css");
        @import url("/css/system.messages.css");
        @import url("/css/system.theme.css");
        @import url("/css/radix_layouts.css");
        @import url("/css/nuboot_radix.style.css");
        @import url("/css/colorizer.css");
        @import url("/css/full_width.css");
        @import url("/css/icons.css");
        @import url("/css/dkan_topics.css");
        @import url("/css/dkan_dataset.css");
        @import url("/css/lkdh_custom.css");
        @import url("/css/dkan-flaticon.css");
        @import url("/css/fonts/flaticon.css");
        @import url("/css/leaflet.groupedlayercontrol.css");
    </style>
    <link type="text/css" rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans::400,300,700" media="all" />

    <script src="/js/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/dt/dt-1.10.13/datatables.min.css"/>

    <script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="//unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <link rel="stylesheet" href="/css/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="/css/MarkerCluster.css" />
    <script src="/js/functions.js"></script>

    <script src="//unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
    <script src="/js/leaflet-bing-layer.min.js"></script>
    <script src='//api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
    <link href='//api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
    <script src="//leaflet.github.io/Leaflet.markercluster/dist/leaflet.markercluster-src.js"></script>
    <script src="/js/leaflet.groupedlayercontrol.js"></script>

    <script src="/js/canvasjs.min.js"></script>
    <script src="/js/spin.min.js"></script>
    <script src="/js/leaflet.spin.min.js"></script>

    <!-- Piwik -->
    <script type="text/javascript">
        var _paq = _paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//api.diepholz.de/piwik/";
            _paq.push(['setTrackerUrl', u+'piwik.php']);
            _paq.push(['setSiteId', '1']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <!-- End Piwik Code -->


</head>


<?php
if($page->fullwidth == true)
    echo "<body class=\"html front not-logged-in no-sidebars page-start panel-layout-full_width panel-region-middle panel-region-top-first panel-region-top-second\">";
else
    echo "<body class=\"html not-front not-logged-in no-sidebars\">";
?>

<div id="skip-link">
    <a href="#main-content" class="element-invisible element-focusable">Direkt zum Inhalt</a>
</div>
<header id="header" class="header" role="header">
    <div class="branding container">
        <a class="logo navbar-btn pull-left" href="/" title="Startseite">
            <img src="/images/logo.png" alt="Startseite" />
        </a>
        <!-- views exposed search -->
        <section id="block-dkan-sitewide-dkan-sitewide-search-bar" class="block block-dkan-sitewide block-- clearfix">

            <div class="content noprint">
                <form action="/datensaetze" method="get" id="dkan-sitewide-dataset-search-form" accept-charset="UTF-8" role="form">
                    <div>
                        <div class="form-item form-type-textfield form-item-search form-group">
                            <label for="edit-search">Search </label>
                            <input placeholder="Suchen" class="form-control form-text" type="text" id="edit-search" name="search" value="" size="30" maxlength="128" />
                        </div>
                        <input type="submit" value="" class="form-submit btn btn-default btn-primary" />
                    </div></form>  </div>

        </section>
    </div>
    <div class="navigation-wrapper">
        <div class="container">
            <nav class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div> <!-- /.navbar-header -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul id="main-menu" class="menu nav navbar-nav">
                        <li class="first leaf menu-link-startseite"><a href="/" title="">Startseite</a></li>
                        <li class="leaf menu-link-datensätze"><a href="/datensaetze" title="">Datensätze</a></li>
                        <li class="leaf menu-link-gruppen"><a href="/gruppen" title="">Gruppen</a></li>
                        <li class="last menu-link-apps"><a href="/apps">Apps</a></li>
                    </ul>

                    <!-- user menu -->
                    <section id="block-dkan-sitewide-dkan-sitewide-user-menu" class="block block-dkan-sitewide block-- clearfix">

                        <div class="content">
                            <span class="links"><a href="/beschreibung-der-programmierschnittstelle-api">API</a></span>
                            <span class="links"><a href="https://www.diepholz.de/portal/seiten/datenschutz-1001642-21750.html?titel=Datenschutz">Datenschutz</a></span>
                            <span class="links"><a href="https://www.diepholz.de/portal/seiten/impressum-1000702-21750.html?titel=Impressum">Impressum</a></span>
                        </div>

                    </section>
                </div><!-- /.navbar-collapse -->
            </nav><!-- /.navbar -->
        </div><!-- /.container -->
    </div> <!-- /.navigation -->
</header>

<div id="main-wrapper">
    <?php
            include("show/".$page->request_module.".php");
          ?>
</div> <!-- /#main-wrapper -->

<footer id="footer" class="footer" role="footer">
    <div id="footer-container" class="container">
        <small class="copyright pull-left"><p>Erstellt mit <a target="_blank" href="http://nucivic.com/dkan">DKAN</a>
                , ein Projekt von <a target="_blank" href="http://nucivic.com">NuCivic</a> |
                Programmierschnittstelle:<a href="/beschreibung-der-programmierschnittstelle-api">Formate XML+JSON+CSV</a> über URL abrufen</p><br>
        </small>
        <small class="pull-right"></small>
    </div>
</footer>
</body>
<script src="/js/bootstrap.min.js"></script>
</html>
<?php }?>
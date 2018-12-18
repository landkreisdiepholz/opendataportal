<div id="main" class="main container">

    <h2 class="element-invisible">Sie sind hier</h2><div class="breadcrumb"><span class="inline odd first"><a href="/">Startseite</a></span> <span class="delimiter">»</span> <span class="inline even last">Beschreibung der Programmierschnittstelle (API)</span></div>

    <div class="main-row">

        <section>
            <a id="main-content"></a>
            <h1 class="page-header">Beschreibung der Programmierschnittstelle (API)</h1>
            <div class="region region-content">
                <article class="node node-page clearfix" about="/beschreibung-der-programmierschnittstelle-api" typeof="sioc:Item foaf:Document">


                    <span property="dc:title" content="Beschreibung der Programmierschnittstelle (API)" class="rdf-meta element-hidden"></span>

                    <div class="content">
                        <div class="field field-name-body field-type-text-with-summary field-label-hidden"><div class="field-items"><div class="field-item even" property="content:encoded">

                                        <p>Das OpenData-Portal des Landkreises Diepholz stellt mit der URL <a href="http://daten.diepholz.de">http://daten.diepholz.de</a>
                                            alle Daten im anwenderfreundlichen CSV-Format (Excel) zum Download zur Verfügung. Das Feldtrennzeichen ist standardmäßig ein Semikolon. Bei jeder Ressource finden Sie den Download-Button, um die Daten als Datei herunterzuladen.
                                            <br><br>
                                            Im OpenData-Portal können alle vorhandenen Ressourcen (Informationen) weiterhin als Tabelle, Diagramm, Cluster oder Karte direkt visuell angezeigt und ausgewertet werden, die Benutzung ist selbsterklärend. Bei jeder Ressource finden Sie den Button „Anzeigen“.
                                            <br><br>
                                            Soll Ihrerseits die elektronische Weiterverarbeitung von Daten realisiert werden, sind neben dem CSV-Format (Excel) üblicherweise die Formate JSON, JSPONP oder XML gewünscht. Auch diese Formate stellt das OpenData-Portal bereit. Um vorhandene Ressourcen in diesen Formaten abzugreifen, kommt die DKAN-API zum Einsatz. Eine vollständige Dokumentation der DKAN-API ist unter docs.getdkan.com zu finden. Möchten Sie die Programmierschnittstelle (API) einsetzen, ist es nötigt, zunächst die ID einer Ressource zu ermitteln. Diese ID finden Sie heraus, indem Sie innerhalb einer Ressource den Button „Anzeigen“ betätigen und dort den Button „Data API“ anklicken. Die „Ressource ID“ ist im gesamten System eindeutig und muss in der URL bei jedem Datenabruf als Parameter angegeben werden.
                                        </p><br>
                                    <p>Nachfolgend einige Beispiele, mit welchen Kommandos die Schnittstelle (Data-API) bedient werden kann.<br>
                                    </p>
                                    <h2>Anzahl Einträge</h2>
                                    <p>Wird die Programmierschnittstelle ohne Angabe der beiden Parameter „limit“ und „offset“ benutzt,
                                        so werden standardmäßig stets die ersten 100 Einträge aus der Datenbank ausgelesen und im gewünschten Format ausgeliefert.
                                        Soll die Schnittstelle pauschal alle Einträge ausgeben, kann bei dem Parameter „limit“ der Wert „-1“ angegeben werden.
                                    </p>
                                    <h2>Verschiedene Formate abrufen</h2>

                                    <p><strong>Abfragebeispiel: (JSON), um die ersten 10 Treffer anzuzeigen (Parameter &limit=10):</strong><br>
                                        <a href="http://daten.diepholz.de/api/action/datastore/search.json?resource_id=743c9debd508c6234773cbb9d22b529b&limit=10">
                                            search.json?resource_id=743c9debd508c6234773cbb9d22b529b&limit=10</a><br>

                                        <br><strong>Abfragebeispiel: (JSON), um die nächsten 10 Treffer zu bekommen (Parameter limit=10&amp;offset=10):</strong><br>
                                        <a href="http://daten.diepholz.de/api/action/datastore/search.json?resource_id=743c9debd508c6234773cbb9d22b529b&limit=10&offset=10">
                                            search.json?resource_id=743c9debd508c6234773cbb9d22b529b&limit=10&offset=10</a><br>
                                        <br><strong>Abfragebeispiel: (XML), um die ersten 10 Treffer anzuzeigen (Parameter &limit=10):</strong><br>
                                        <a href="http://daten.diepholz.de/api/action/datastore/search.xml?resource_id=743c9debd508c6234773cbb9d22b529b&limit=10">
                                            search.xml?resource_id=743c9debd508c6234773cbb9d22b529b&limit=10</a><br>

                                        <br><strong>Abfragebeispiel: (CSV), Liefert immer ALLE Daten</strong><br>
                                        <a href="http://daten.diepholz.de/download/743c9debd508c6234773cbb9d22b529b">download/743c9debd508c6234773cbb9d22b529b</a><br>
                                    </p>
                                        <h2>Quellcodebeispiele</h2>
                                    <p><strong>Beispiel Java-Script:</strong></p>
                                    <blockquote><p>var data = {<br>
                                            resource_id: '743c9debd508c6234773cbb9d22b529b', // the resource id<br>
                                            limit: 5, // get 5 results<br>
                                            };<br>
                                            $.ajax({<br>
                                            url: '<a href="http://daten.diepholz.de/api/action/datastore/search.json">http://daten.diepholz.de/api/action/datastore/search.json</a>',<br>
                                            data: data,<br>
                                            dataType: 'json',<br>
                                            success: function(data) {<br>
                                            alert('Total results found: ' + data.result.total)<br>
                                            }<br>
                                            });</p>
                                    </blockquote>
                                    <p><strong>Beispiel PHP:</strong></p>
                                    <blockquote><p>$content = file_get_contents("http://daten.diepholz.de/api/action/datastore/search.json?resource_id=743c9debd508c6234773cbb9d22b529b");<br>
                                            $daten = json_decode($content);<br>
                                            print_r($daten);</p>
                                    </blockquote>
                                    <p><strong>Beispiel Python:</strong></p>
                                    <blockquote><p>import urllib<br>
                                            url = 'http://daten.diepholz.de/api/action/datastore/search.json?resource_id=743c9debd508c6234773cbb9d22b529b'<br>
                                            fileobj = urllib.urlopen(url)<br>
                                            print fileobj.read()</p>
                                    </blockquote>
                                </div></div></div>  </div>



                </article>
            </div>
        </section>

    </div>

</div>
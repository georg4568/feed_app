<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<?php

$url = "https://flipboard.com/@raimoseero/feed-nii8kd0sz.rss";

$contents = file_get_contents($url);

$xml = simplexml_load_string($contents);


foreach ($xml->channel as $channel) {
    foreach ($channel->item as $item) {

        $children = $item->children();
        $ns = $item->getNamespaces(true);


        echo "<div class='feed' onclick=showArticle(\"$children->link\")>";
        echo "<div class='text_container'>";

        if (isset($ns["media"])) {
            $media = $item->children($ns["media"]);
            $url = $media->content->attributes()->url;
            echo "<img src='$url'/>";
        }

        $desc = html_entity_decode($children->description);

        echo "<h3 class='title'>$children->title</h3>";
        echo "<div class='desciption'>$desc</div>";
        echo "<div class='pubDate'>$children->pubDate</div>";
        echo "</div>";


        echo "</div>";

    }
}

?>
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="article">
            <h1 id="article_title"></h1>
            <div id="article_excerpt"></div>
            <div id="article_content"></div>
        </div>
    </div>

</div>
<script>

    var modal = document.getElementById('myModal');


    var span = document.getElementsByClassName("close")[0];


    span.onclick = function () {
        modal.style.display = "none";

        var iframe = modal.querySelector('iframe');
        var video = modal.querySelector('video');
        if (iframe) {
            var iframeSrc = iframe.src;
            iframe.src = iframeSrc;
        }
        if (video) {
            video.pause();
        }
    }


    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
		
	var iframe = modal.querySelector('iframe');
        var video = modal.querySelector('video');
        if (iframe) {
            var iframeSrc = iframe.src;
            iframe.src = iframeSrc;
        }
        if (video) {
            video.pause();
        }
    }


    function showArticle(url) {

        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {

                var article = JSON.parse(this.responseText);

                document.getElementById("article_title").innerHTML = article.title;
                document.getElementById("article_excerpt").innerHTML = article.excerpt;


                var htmlObject = document.createElement('div');
                htmlObject.innerHTML = article.content;

                document.getElementById("article_content").innerHTML = htmlObject.innerHTML;

                modal.style.display = "block";

                var images = document.getElementById("article_content").getElementsByTagName("img");
                var iframes = document.getElementById("article_content").getElementsByTagName("iframe");


                for (var i = 0; i < images.length; i++) {

                    images[i].removeAttribute("srcset"); //images are not shown for some reason
                    images[i].style.width = '80%';
                    images[i].style.minWidth = '300px';

                }

                for (i = 0; i < iframes.length; i++) {


                    iframes[i].style.border = 'none'; //frame does not scale proportionally so I hide it
                    iframes[i].style.width = '80%';
                    iframes[i].style.minWidth = '300px';

                }



            }
        }
        xmlhttp.open("GET", "https://mercury.postlight.com/parser?url=" + url, true);
        xmlhttp.setRequestHeader("Content-Type", "application/json");
        xmlhttp.setRequestHeader("x-api-key", "Y2FvavMSbi7PVpYrqtRAOumBgATI9YmBGQ4xB0rV");
        xmlhttp.send();
    }
</script>

</body>
</html>
<?php

<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($urls as $url){ ?>
        <url>
            <loc><?=$host.$url[0]?></loc>
            <changefreq><?=$url[1]?></changefreq>
        </url>
    <?php } ?>
</urlset>
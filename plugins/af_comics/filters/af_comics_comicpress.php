<?php
class Af_Comics_ComicPress extends Af_ComicFilter {

    public function supported() {
        return array("Buni", "Buttersafe", "Happy Jar", "CSection",
            "Extra Fabulous Comics", "Nedroid", "Stonetoss");
    }

    public function process(&$article) {
        if (strpos($article["guid"], "bunicomic.com") !== false ||
                strpos($article["guid"], "buttersafe.com") !== false ||
                strpos($article["guid"], "extrafabulouscomics.com") !== false ||
                strpos($article["guid"], "happyjar.com") !== false ||
                strpos($article["guid"], "nedroid.com") !== false ||
                strpos($article["guid"], "stonetoss.com") !== false ||
                strpos($article["guid"], "csectioncomics.com") !== false) {

                // lol at people who block clients by user agent
                // oh noes my ad revenue Q_Q

                $res = fetch_file_contents(["url" => $article["link"],
                    "useragent" => "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)"]);

                $doc = new DOMDocument();

                if (@$doc->loadHTML($res)) {
                    $xpath = new DOMXPath($doc);
                    $basenode = $xpath->query('//div[@id="comic"]')->item(0);

                    if ($basenode) {
                        $article["content"] = $doc->saveHTML($basenode);
                        return true;
                    }

                    // buni-specific
                    $webtoon_link = $xpath->query("//a[contains(@href,'www.webtoons.com')]")->item(0);

                    if ($webtoon_link) {

                        $res = fetch_file_contents(["url" => $webtoon_link->getAttribute("href"),
                            "useragent" => "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)"]);

                        if (@$doc->loadHTML($res)) {
                            $xpath = new DOMXPath($doc);
                            $basenode = $xpath->query('//div[@id="_viewerBox"]')->item(0);

                            if ($basenode) {
                                $imgs = $xpath->query("//img[@data-url]", $basenode);

                                foreach ($imgs as $img) {
                                    $img->setAttribute("src", $img->getAttribute("data-url"));
                                }

                                $article["content"] = $doc->saveHTML($basenode);
                                return true;
                            }
                        }
                    }
                }
        }

        return false;
    }
}

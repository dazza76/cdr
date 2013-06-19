<?php
header("Content-Type: text/html; charset=UTF-8");

//[HTTP_USER_AGENT]
// Chrom  => Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22
//IE      => Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)
//Opera   => Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14
//FF      => Mozilla/5.0 (Windows NT 6.1; WOW64; rv:19.0) Gecko/20100101 Firefox/19.0
//Safari  => Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2
?>
<html>
    <head>
        <title>Вы используете устаревший браузер.</title>

        <style>
            html, body {
                width: 100%;
                height: 100%;
                background: #F7F7F7;
                padding: 0px;
                margin: 0px;
            }
            body {
                color: #000;
                direction: ltr;
                font-family: tahoma, arial, verdana, sans-serif, Lucida Sans;
                font-size: 11px;
                font-weight: normal;
            }
            #bad_browser {
                position: absolute;
                left: 50%;
                top: 50%;
                text-align: center;
                width: 530px;
                margin: -200px 0px 0px -250px;
                background: #FFF;
                line-height: 180%;
                border-bottom: 1px solid #E4E4E4;
                -webkit-box-shadow: 0 0 3px rgba(0, 0, 0, 0.15);
                -moz-box-shadow: 0 0 3px rgba(0, 0, 0, 0.15);
                box-shadow: 0 0 3px rgba(0, 0, 0, 0.15);
            }
            #content {
                padding: 20px;
                font-size: 1.19em;
            }
            #content div {
                margin: 10px 0 15px 0;
            }
            #content #browsers {
                width: 480px;
                height: 136px;
                margin: 15px auto 0px;
            }
            a {
                color: #2B587A;
                text-decoration: none;
                cursor: pointer;
            }
            #browsers a {
                float: left;
                width: 120px;
                height: 20px;
                padding: 106px 0px 13px 0;
                -webkit-border-radius: 4px;
                -khtml-border-radius: 4px;
                -moz-border-radius: 4px;
                border-radius: 4px;
            }
            #browsers a:hover {
                text-decoration: none;
                background-color: #edf1f5!important;
            }
        </style>
        <!--[if lte IE 8]>
        <style>
        #bad_browser {
          border: none;
        }
        #wrap {
          border: solid #C3C3C3;
          border-width: 0px 1px 1px;
        }
        #content {
          border: solid #D9E0E7;
          border-width: 0px 1px 1px;
        }
        </style>
        <![endif]-->

    </head>

    <body>

        <div id="bad_browser">
            <div id="wrap">
                <div id="content">
                    Для работы с сайтом необходима поддержка Javascript и CSS.
                    <div>
                        Чтобы использовать все возможности сайта, загрузите и установите один из этих браузеров:
                        <div id="browsers" style="width: 360px;"><a href="http://www.google.com/chrome/" target="_blank" style="background: url(images/chrome.png) no-repeat 50% 17px;">Chrome</a>
                            <a href="http://www.mozilla-europe.org/" target="_blank" style="background: url(images/firefox.png) no-repeat 50% 17px;">Firefox</a>
                            <a href="http://www.opera.com/" target="_blank" style="background: url(images/opera.png) no-repeat 50% 15px;">Opera</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
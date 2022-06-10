{__NOLAYOUT__}<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>发生错误</title>
    <meta name="robots" content="noindex,nofollow"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link rel="shortcut icon" href="/assets/img/favicon.ico"/>
    <style>
        * {
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        html, body, div, span, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, abbr, address, cite, code, del, dfn, em, img, ins, kbd, q, samp, small, strong, sub, sup, var, b, i, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, caption, article, aside, canvas, details, figcaption, figure, footer, header, hgroup, menu, nav, section, summary, time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            vertical-align: baseline;
            background: transparent;
        }

        article, aside, details, figcaption, figure, footer, header, hgroup, nav, section {
            display: block;
        }

        html {
            font-size: 16px;
            line-height: 24px;
            width: 100%;
            height: 100%;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        img {
            vertical-align: middle;
            max-width: 100%;
            height: auto;
            border: 0;
            -ms-interpolation-mode: bicubic;
        }

        body {
            min-height: 100%;
            background: #edf1f4;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-family: "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", 微软雅黑, Arial, sans-serif;
        }

        .clearfix {
            clear: both;
            zoom: 1;
        }

        .clearfix:before, .clearfix:after {
            content: "\0020";
            display: block;
            height: 0;
            visibility: hidden;
        }

        .clearfix:after {
            clear: both;
        }

        body.error-page-wrapper, .error-page-wrapper.preview {
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }

        .error-page-wrapper .content-container {
            border-radius: 2px;
            text-align: center;
            box-shadow: 1px 1px 1px rgba(99, 99, 99, 0.1);
            padding: 50px;
            background-color: #fff;
            width: 100%;
            max-width: 720px;
            position: absolute;
            left: 50%;
            top: 50%;
            margin-top: -220px;
            margin-left: -280px;
        }

        .error-page-wrapper .content-container.in {
            left: 0px;
            opacity: 1;
        }

        .error-page-wrapper .head-line {
            transition: color .2s linear;
            font-size: 40px;
            line-height: 60px;
            letter-spacing: -1px;
            margin-bottom: 20px;
            color: #777;
        }

        .error-page-wrapper .subheader {
            transition: color .2s linear;
            font-size: 24px;
            line-height: 46px;
            color: #494949;
        }

        .error-page-wrapper .hr {
            height: 1px;
            background-color: #eee;
            width: 80%;
            max-width: 350px;
            margin: 25px auto;
        }

        .error-page-wrapper .context {
            transition: color .2s linear;
            font-size: 16px;
            line-height: 27px;
            color: #aaa;
        }

        .error-page-wrapper .context #wait{
            padding-left: 10px;
            padding-right: 10px;
            font-size: 20px;
        }

        .error-page-wrapper .context p {
            margin: 0;
        }

        .error-page-wrapper .context p:nth-child(n+2) {
            margin-top: 16px;
        }

        .error-page-wrapper .buttons-container {
            margin-top: 35px;
            overflow: hidden;
        }

        .error-page-wrapper .buttons-container a {
            transition: text-indent .2s ease-out, color .2s linear, background-color .2s linear;
            text-indent: 0px;
            font-size: 14px;
            text-transform: uppercase;
            text-decoration: none;
            color: #fff;
            background-color: #2ecc71;
            border-radius: 99px;
            padding: 8px 0 8px;
            text-align: center;
            display: inline-block;
            overflow: hidden;
            position: relative;
            width: 45%;
        }

        .error-page-wrapper .buttons-container a:hover {
            text-indent: 15px;
        }

        .error-page-wrapper .buttons-container a:nth-child(1) {
            float: left;
        }

        .error-page-wrapper .buttons-container a:nth-child(2) {
            float: right;
        }

        @media screen and (max-width: 580px) {
            .error-page-wrapper {
                padding: 30px 5%;
            }

            .error-page-wrapper .content-container {
                padding: 37px;
                position: static;
                left: 0;
                margin-top: 0;
                margin-left: 0;
            }

            .error-page-wrapper .head-line {
                font-size: 36px;
            }

            .error-page-wrapper .subheader {
                font-size: 27px;
                line-height: 37px;
            }

            .error-page-wrapper .hr {
                margin: 30px auto;
                width: 215px;
            }
        }

        @media screen and (max-width: 450px) {
            .error-page-wrapper {
                padding: 30px;
            }

            .error-page-wrapper .head-line {
                font-size: 32px;
            }

            .error-page-wrapper .hr {
                margin: 25px auto;
                width: 180px;
            }

            .error-page-wrapper .context {
                font-size: 15px;
                line-height: 22px;
            }

            .error-page-wrapper .context p:nth-child(n+2) {
                margin-top: 10px;
            }

            .error-page-wrapper .buttons-container {
                margin-top: 29px;
            }

            .error-page-wrapper .buttons-container a {
                float: none !important;
                width: 65%;
                margin: 0 auto;
                font-size: 13px;
                padding: 9px 0;
            }

            .error-page-wrapper .buttons-container a:nth-child(2) {
                margin-top: 12px;
            }
        }
    </style>
</head>
<body class="error-page-wrapper">
<div class="content-container">
    <div class="head-line">
        <?php switch ($code) {?>
            <?php case 1:?>
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACCCAMAAACTmMFdAAABa1BMVEX////39/fw8/Ds9Ozp8OXf5uLg5+DS6NLQ4NDH3sfC3L+/27rA27q40rO71bO41LOwz6iu0qmpy6KlyqCmyZ6my6Ciypygx5ufyJmeyJiaxpSax5SYwpOZw5GYw5KWwo+Tvo6SwYuTwYySwYuTvoyRvoqPvoiQv4WNvoWOvISNvYSNvYSMu4KMvYOLvIGLu4CIuoCHu4CIu3+Gun6Iun+Gu36FunyEuHuDuHmCt3qDuHmBuXeBt3aAtnV/t3SAt3N+tnN9tHN8s3F9tHF8tnF8s3B8s255tG16s214sm15smx3smt3s2t2smlzsGZyr2NvrmJvrmBvr2FrrVxqrltorVhmq1VjqlNkqlNiqlBjqlFiqU5eqUpdqEpcqEddqEhcqEZcqEdZp0ZZpkNXpz9Xp0BVpj5UpTlVpTpVpTtWpTtMpDFNpDFNpDJOpDNOpDRPpDRPpDVQpDZQpTZQpTdRpTdRpThSpThNozKC2EVgAAAAeHRSTlM9P0JDRUdJT1FWWFxdYWFkamtxdHZ2eXt9foGDhoeIi46PkJGSlJaXmJmam52eoKGjpKWmp6iprK2ur7K0tbe4uru9vr/AwsTFxsbJyczS1dfa2+Di5urt7e/w8vX29/f4+Pn6+/v8/f39/f7+/v7+/v7+/v7+/v4CK1X7AAABx0lEQVQYGe3B+TuUUQAF4ENGK4WaFkJKpX2lSKlJq6bSHto0jL75hrHN+fOraWLm3hE/3Xufp/O+EBERERERERERERER+R/VHUsNtSI0bZMko5eNCErHCksmGhGQzmWWfUwgGF1LXPO+AYHoXmSF5wjD8QIrxW0IQW+B1UYQgFMLNKTh35l5mm7Cu3N5mlZ3w7cLeVpuwLdLMS1P4NvVmJYH8K0/puU+fBvI0RTdg2+DOZqiEbixpy+5DTUNRTRFt+FE0weSM1dQw3BEUzQMJ/ZnWfIqAdOdiKZoCE60zLBsai+q3Y1oyg3CieYM12R7UClFS24ATtR/YoV4FOtGaYn74cYjVnvTiLKHtMTX4MhnGr62oOQxLfFluLJEU/E0fhmjJX8RznyjJXoKPKMlfx7uvGANb9O05M/Coe0Zbsl8H5xqneYWLJyEYzvecVOFXjhXl+YmCj3w4XqB/7LYDT8OfefGlrrgy64JbmS5E/7Uj7O2lQ54dSvPGlbb4dnRH7QUj8C7pkkaZg8jAInXrJI9iDCkYq7LJhGKE7P8a+4AwrHvC/8oJhGSnVP8ba4dYWkYm17IjDdDREREREREREREREREvPkJlJnW6Ga6nSAAAAAASUVORK5CYII=" class="pulse"/>
            <?php break;?>
            <?php case 0:?>
            <img src="data:image/png;base64,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"/>
            <?php break;?>
        <?php } ?>
    </div>
    <div class="subheader"><?php echo(strip_tags($msg));?></div>
    <div class="hr"></div>
    <div class="context">
        <p>等待时间：<b id="wait"><?php echo($wait);?></b>页面自动跳转</p>
    </div>
    <div class="buttons-container">
        <a href="/">返回主页</a>
        <a id="href" href="<?php echo($url);?>">跳转</a>
    </div>
</div>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),
            href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>
</body>
</html>
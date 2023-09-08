<!doctype html>
<html>
<?php
    $this->load->view('partials/assets');
    $navigation = $this->load->view('partials/navigation', array(), true);
    $this->load->view('partials/eucookie');
?>
<head>
    <title><?=$title?></title>
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ShareBOX aplikacija">
    <meta name="author" content="Djordje Ungar">
    <?php echo $this->assets->render_group(DOCUMENT_HEAD_START); ?>
    <?php echo $this->assets->render_group(DOCUMENT_HEAD); ?>
    <!--[if lt IE 9]>
    <script src="/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
    <?php echo $this->assets->render_group(DOCUMENT_BODY_START); ?>
    <div id="wrapper">
        <?php echo $navigation;?>
        <?php $this->load->view('partials/message'); ?>
        <div id="page-wrapper" style="min-height: 484px;">
            <?php echo $content;?>
        </div>
    </div>
    <?php echo $this->assets->render_group(DOCUMENT_BODY_END); ?>
</body>
</html>

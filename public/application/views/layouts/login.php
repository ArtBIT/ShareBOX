<!doctype html>
<html>
<?php
    $this->load->view('partials/assets');
    $navigation = $this->load->view('partials/navigation', array(), true);
?>
<head>
    <title><?=$title?></title>
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
    <?php echo $this->assets->render_group(DOCUMENT_HEAD_START); ?>
    <?php echo $this->assets->render_group(DOCUMENT_HEAD); ?>
    <!--[if lt IE 9]>
    <script src="/js/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
    <?php echo $this->assets->render_group(DOCUMENT_BODY_START); ?>
    <?php echo $navigation;?>
    <?php echo $content;?>
    <?php echo $this->assets->render_group(DOCUMENT_BODY_END); ?>
</body>
</html>

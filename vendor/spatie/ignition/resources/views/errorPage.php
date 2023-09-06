<!DOCTYPE html>
<?php /** @var \Spatie\Ignition\ErrorPage\ErrorPageViewModel $viewModel */ ?>
<html lang="en" class="<?= $viewModel->theme() ?>">
<!--
<?= $viewModel->throwableString() ?>
-->
<head>
    <!-- Hide dumps asap -->
    <style>
        pre.sf-dump {
            display: none !important;
        }
    </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow">

    <title><?= $viewModel->title() ?></title>

    <script>
        // Livewire modals remove CSS classes on the `html` element so we re-add
        // the theme class again using JavaScript.
        document.documentElement.classList.add('<?= $viewModel->theme() ?>');

        // Process `auto` theme as soon as possible to avoid flashing of white background.
        if (document.documentElement.classList.contains('auto') && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
    </script>

    <style><?= $viewModel->getAssetContents('ignition.css') ?></style>

    <?= $viewModel->customHtmlHead() ?>

</head>
<body class="scrollbar-lg antialiased bg-center bg-dots-darker dark:bg-dots-lighter">

<script>
    window.data = <?=
        $viewModel->jsonEncode([
            'report' => $viewModel->report(),
            'shareableReport' => $viewModel->shareableReport(),
            'config' => $viewModel->config(),
            'solutions' => $viewModel->solutions(),
            'updateConfigEndpoint' => $viewModel->updateConfigEndpoint(),
        ])
    ?>;
</script>

<!-- The noscript representation is for HTTP client like Postman that have JS disabled. -->
<noscript>
    <pre><?= $viewModel->throwableString() ?></pre>
</noscript>

<div id="app"></div>

<script>
    <!--
    <?= $viewModel->getAssetContents('ignition.js') ?>
    -->
</script>

<script>
    window.ignite(window.data);
</script>

<?= $viewModel->customHtmlBody() ?>

<!--
<?= $viewModel->throwableString() ?>
-->
</body>
</html>

<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-category" data-toggle="tooltip"
                        title="<?php echo $button_save; ?>"
                        class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip"
                   title="<?php echo $button_cancel; ?>"
                   class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li>
                    <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i
                    class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php }
            if ($projectId) { ?>
        <div class="alert alert-success">
            <i class="fa fa-exclamation-circle"></i> Поздравляем! Ваш сайт успешно привязан к
            аккаунту <a href="https://intarget.ru" target="_blank">inTarget.ru</a>.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i><?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <p><b>inTarget</b> — сервис повышения продаж и аналитика посетителей сайта.</p>

                <p>Оцените принципиально новый подход к просмотру статистики. Общайтесь со своей
                    аудиторией, продавайте
                    лучше, зарабатывайте больше. И все это бесплатно!</p>
                <hr>

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data"
                      id="form-category"
                      class="form-horizontal">
                    <?php /*if ($projectId) { ?>
                    <?php echo $entry_projectId . ' ' . $projectId?>
                    <? }*/ ?>
                    <div id="settings"
                    <?php /*if ($projectId) { echo "style='display:none'"; }*/ ?>>
                    <input id="projectId" type="hidden" name="intarget[projectId]"
                           value="<?php echo $projectId; ?>"/>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"
                               for="input-name"><?php echo $text_email; ?></label>

                        <div class="col-sm-10">
                            <input type="text" name="intarget[email]"
                                   value="<?php echo isset($email) ? $email : ''; ?>"
                                   placeholder="<?php echo $email_placeholder; ?>" id="input-name"
                                   class="form-control" size="50"
                                   style="width: auto; display: inline-block" <?php if ($projectId) { echo "disabled"; } ?>
                            /> <?php if ($projectId) { echo '<i class="fa fa-check fa-2x text-success"></i>
                            '; } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"
                               for="input-name"><?php echo $text_key; ?></label>

                        <div class="col-sm-10">
                            <input type="text" name="intarget[key]"
                                   value="<?php echo isset($key) ? $key : ''; ?>"
                                   placeholder="<?php echo $key_placeholder; ?>" id="input-name"
                                   class="form-control" size="50"
                                   style="width: auto; display: inline-block" <?php if ($projectId) { echo "disabled"; } ?>
                            /> <?php if ($projectId) { echo '<i class="fa fa-check fa-2x text-success"></i>
                            '; } ?>
                        </div>
                    </div>
                    <div class="form-group" style="display: none; visibility: hidden">
                        <label class="col-sm-2 control-label"
                               for="input-name"><?php echo $entry_url; ?></label>

                        <div class="col-sm-10">
                            <input type="text" name="intarget[url]" value="<?php echo $url; ?>"
                                   placeholder="<?php echo $entry_url; ?>" id="input-name"
                                   class="form-control"/>
                        </div>
                    </div>
            </div>
            </form>
            <?php if (!$projectId) { ?>
            <div class="help_msg">
                <p>Введите email и ключ API из личного кабинета <a href="https://intarget.ru"
                                                                   target="_blank">inTarget.ru</a>
                </p>

                <p>Если вы ещё не зарегистрировались в сервисе inTarget это можно сделать по ссылке
                    <a href="https://intarget.ru" target="_blank">inTarget.ru</a></p>
            </div>
            <?php } ?>
            <hr>
            <div class="help_succ_msg" style="display: none;">
                <p><?php echo $succ_mess1;?> <a href="https://intarget.ru" target="_blank">inTarget.ru</a> <? echo $succ_mess2;?>
                </p>
            </div>
            <?php if ($projectId) {
               echo "<p>";
            echo $succ_mess1;?> <a href="https://intarget.ru" target="_blank">inTarget.ru</a> <? echo $succ_mess2;
               echo "</p>";
            }
            if (!$projectId) { ?>
            <button onclick="apply();" type="button" data-toggle="tooltip"
                    title="<?php echo $button_apply; ?>"
                    class="btn btn-success"
                    style="margin-bottom: 20px;display: block;"><?php echo $auth; ?>
            </button>
            <?php } ?>

            <?php echo '<p>';
            echo $tech_support;?> <a href="mailto:plugins@intarget.ru">plugins@intarget.ru</a></p>
            <p>Opencart inTarget ver.<?php echo $ver;?></p>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    function apply() {
        $form = $('#form-category');
        $(".alert").remove();
        $.post('<?php echo $action_register; ?>', $form.serialize())
                .done(
                function (json) {
                    if (json.result == 'success') {
                        $('.form-control').after('<i class="fa fa-check fa-2x text-success"></i>');
                        $('#projectId').val(json.projectId);
                        $('.btn-success').hide();
                        $('.help_msg').hide();
                        $('.help_succ_msg').show();
                        $('.form-control').attr('disabled', 'disabled');
                        switch (json.code) {
                            case 200:
                                $('.panel.panel-default').before('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> Поздравляем! Ваш сайт успешно привязан к аккаунту <a href="https://intarget.ru" target="_blank">inTarget.ru</a>. <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                                break;
                        }
                    } else if (json.result == 'error') {
                        var errorText = json.code;
                        switch (json.code) {
                            case 403:
                                errorText = ' Неверно введен email или ключ API.';
                                break;
                            case 404:
                                errorText = ' Данный email не зарегистрирован на сайте http://intarget.ru';
                                break;
                            case 500:
                                errorText = ' Данный сайт уже используется на сайте http://intarget.ru';
                                break;
                            case 400:
                                errorText = 'Заполните обязательные поля';
                                break;
                        }
                        $('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + errorText +
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                '</div>');
                    }
                }
        );
    }
</script>
<?php echo $footer; ?>

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
            if ($intarget_id) { ?>
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
                <p><b>inTarget</b> &mdash; сервис повышения продаж и аналитика посетителей сайта.</p>

                <p>Оцените принципиально новый подход к просмотру статистики. Общайтесь со своей
                    аудиторией, продавайте
                    лучше, зарабатывайте больше. И все это бесплатно!</p>
                <hr>

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data"
                      id="form-category"
                      class="form-horizontal">
                    <?php /*if ($intarget_id) { ?>
                    <?php echo $entry_intarget_id . ' ' . $intarget_id?>
                    <? }*/ ?>
                    <div id="settings"
                    <?php /*if ($intarget_id) { echo "style='display:none'"; }*/ ?>>

                    <input id="intarget_id" type="hidden" name="intarget_id"
                           value="<?php echo isset($intarget_id) ? $intarget_id : ''; ?>"/>

                    <input id="intarget_code" type="hidden" name="intarget_code" value="<?php echo isset($intarget_code) ? trim(htmlspecialchars($intarget_code)) : '' ; ?>">

                    <div class="form-group <?php if (!$intarget_id) echo "required";?>">
                        <label class="col-sm-2 control-label"
                               for="input-name"><?php echo $text_email; ?></label>

                        <div class="col-sm-10">
                            <input type="text" name="intarget_email" id="intarget_email"
                                   value="<?php echo isset($intarget_email) ? $intarget_email : ''; ?>"
                                   placeholder="<?php echo $email_placeholder; ?>"
                                   class="form-control" size="50"
                                   style="width: auto; display: inline-block" <?php if ($intarget_id) { echo "disabled"; } ?>/>
                            <?php if ($intarget_id) { echo '<i class="fa fa-check fa-2x text-success"></i>
                            '; } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if (!$intarget_id) echo "required";?>">
                        <label class="col-sm-2 control-label"
                               for="input-name"><?php echo $text_key; ?></label>

                        <div class="col-sm-10">
                            <input type="text" name="intarget_key" id="intarget_key"
                                   value="<?php echo isset($intarget_key) ? $intarget_key : ''; ?>"
                                   placeholder="<?php echo $key_placeholder; ?>"
                                   class="form-control" size="50"
                                   style="width: auto; display: inline-block" <?php if ($intarget_id) { echo "disabled"; } ?>/>
                             <?php if ($intarget_id) { echo '<i class="fa fa-check fa-2x text-success"></i>
                            '; } ?>
                        </div>
                    </div>
                    <div class="form-group" style="display: none; visibility: hidden">
                        <label class="col-sm-2 control-label"
                               for="input-name"></label>

                        <div class="col-sm-10">
                            <input type="text" name="url" value="<?php echo $url; ?>" id="intarget_url"
                                   class="form-control"/>
                        </div>
                    </div>
            </div>
            </form>
            <?php if (!$intarget_id) { ?>
            <div class="help_msg">
                <p>Введите Email и ключ API из личного кабинета <a href="https://intarget.ru"
                                                                   target="_blank">inTarget</a>
                </p>

                <p>Если вы ещё не зарегистрировались в сервисе inTarget это можно сделать по ссылке
                    <a href="https://intarget.ru" target="_blank">inTarget</a></p>
            </div>
            <?php } ?>
            <hr>
            <div class="help_succ_msg" style="display: none;">
                <p><?php echo $succ_mess1;?> <a href="https://intarget.ru" target="_blank">inTarget</a> <? echo $succ_mess2;?>
                </p>
            </div>
            <?php if ($intarget_id) {
               echo "<p>";
            echo $succ_mess1;?> <a href="https://intarget.ru" target="_blank">inTarget</a> <? echo $succ_mess2;
               echo "</p>";
            }
            echo '<p>';
            echo $tech_support;?> <a href="mailto:plugins@intarget.ru">plugins@intarget.ru</a></p>
            <p>Opencart inTarget ver.<?php echo $ver;?></p>
        </div>
    </div>
</div>
</div>
<?php echo $footer; ?>

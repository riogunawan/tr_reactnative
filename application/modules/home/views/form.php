<!-- breadcrumb -->
<div class="body-breadcrumb">
    <div class="container-fluid">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <h4><?php echo $title ?></h4>
        </div>
        <div class="hidden-xs col-sm-6 col-md-6">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="<?php echo $link_back ?>"><?php echo $title ?></a></li>
                <li><a href="#"><?php echo $subtitle ?></a></li>
            </ol>
        </div>
    </div>
</div>
<!-- content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php echo $this->session->flashdata("msg") ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php echo $subtitle ?>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <form class="row form form-horizontal" action="<?= $form_action ?>" method="POST" role="form" enctype="multipart/form-data">

                                <div class="col-md-12">
                                    <?= $input['hide']['id'] ?>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nama Lengkap</label>
                                        <div class="col-md-9">
                                            <?php echo $input["nama_lengkap"] ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Username</label>
                                        <div class="col-md-9">
                                            <?php echo $input["username"] ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Password</label>
                                        <div class="col-md-9">
                                            <?php echo $input["pass"] ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Konfirmasi Password</label>
                                        <div class="col-md-9">
                                            <?php echo $input["pass_confirm"] ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Level</label>
                                        <div class="col-md-9">
                                            <?php echo $input["id_level"] ?>
                                        </div>
                                    </div>

                                    <div class="form-group kabupaten_hide">
                                        <label class="col-md-3 control-label">Kabupaten</label>
                                        <div class="col-md-9">
                                            <?php echo $input["kabupaten"] ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Avatar</label>
                                        <div class="col-md-9">
                                            <p class="label label-danger">max file size 3MB</p>
                                            <label class="control-label avatar" data-avatar="<?= @$avatar ?>"></label>
                                            <input id="avatar" name="avatar" type="file" class="file-loading">
                                        </div>
                                    </div>

                                    <br />

                                </div>

                                <div class="col-md-12">
                                    <div class="btn-group pull-right">
                                        <button class="btn btn-success btn-proses">
                                            <i class="fa fa-check"></i>&nbsp;
                                            Proses
                                        </button>
                                        <a href="<?= @$link_back ?>" class="btn btn-warning">
                                            <i class="fa fa-arrow-left"></i>&nbsp;
                                            Kembali
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
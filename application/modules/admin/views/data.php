<!-- breadcrumb -->
<div class="body-breadcrumb">
    <div class="container-fluid">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <h4><?php echo $title ?></h4>
        </div>
        <div class="hidden-xs col-sm-6 col-md-6">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="<?php echo site_url('admin') ?>"><?php echo $title ?></a></li>
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
                        <span class="pull-right clickable">
                            <i class="fa fa-search" aria-hidden="true" data-toggle="collapse" data-target="#pencarian_rinci" aria-expanded="false" aria-controls="pencarian_rinci"></i>
                        </span>
                    </div>
                    <div class="collapse m-b-20" id="pencarian_rinci">
                        <div class="panel panel-primary">
                            <form class="panel-body form-filter" action="index.html" method="POST">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <?php echo $filter["nama_lengkap"] ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <?php echo $filter["username"] ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Level</label>
                                        <?php echo $filter["id_level"] ?>
                                    </div>
                                </div>
                                <div class="col-md-6 kabupaten_hide">
                                    <div class="form-group">
                                        <label>Kabupaten</label>
                                        <?php echo $filter["kabupaten"] ?>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-sm btn-primary btn-cari" type="submit">
                                        <i class="fa fa-search"></i>&nbsp;
                                        Filter Data
                                    </button>
                                    <button class="btn btn-sm btn-default btn-reset" type="button">
                                        <i class="fa fa-refresh"></i>&nbsp;
                                        Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="row pull-right">
                                <a href="<?php echo $link_add ?>" class="btn btn-success">
                                    <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;
                                    Tambah
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row table-responsive">
                                <table class="table table-bordered dataTable" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="2%" class="text-center">#</th>
                                            <th width="2%">Aksi</th>
                                            <th>Nama Lengkap</th>
                                            <th>Username</th>
                                            <th>Level</th>
                                            <th>Kabupaten</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
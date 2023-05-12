<section class="vh-100" id="home">
    <div class="container-fluid">
    <div class="card p-2 mt-2 d-flex justify-content-center" style="max-width: 30%;">
        <form action="<?=base_url('/Chat')?>" method="get" id="filter">
            <label for="datetime">Filter:</label>
            <input type="hidden" name="jsonId" value="<?=$json?>">
            <input type="datetime-local" id="datetime" name="datetime" step="1">
            <select name="filterType" id="filterType">
                <option>Before</option>
                <option>After</option>
            </select>
        </form>
    </div>
    <?php if(isset($json) && isset($messages)) {
        foreach($messages as $index => $row) { ?>
        <div class="row pb-4 <?=($index==0) ? 'pt-5' : ''?> justify-content-center">
            <div class="chat-record-container">
                <div class="chat-record-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <h5>Message <?=$index + 1?></h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row border-bottom pb-2">
                                    <div class="col-1"><strong>Type: <?=$row->type?></strong></div>
                                    <div class="col-3"><strong>Time: <?=$row->time?></strong></div>
                                </div>
                                
                                <div class="card-data">
                                    <div class="row pt-2">
                                        <div class="col-12">
                                            <?php if($row->type == 'msg') {?>
                                            <strong>Message: </strong> <?=$row->msg?>
                                            <?php } else {?>
                                            <strong>File Data:</strong> <br>
                                                URI: <?=isset($fileData) ? $fileData[$row->id]['uri'] : 'N/A'?> <br>
                                                Name: <?=isset($fileData) ? $fileData[$row->id]['name'] : 'N/A'?> <br>
                                                Mime: <?=isset($fileData) ? $fileData[$row->id]['mime'] : 'N/A'?> <br>
                                            <?php }?>
                                            <hr>
                                            <Strong>Sender Info:</Strong> <br>
                                                t: <?=$senders[$row->id]['t']?> <br>
                                                n: <?=isset($senders[$row->id]['n']) ? $senders[$row->id]['n'] : 'N/A'?> <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    <?php }
    } ?>
           
        

           <div class="upload-popup" >
            <div class="upload-popup-content">
                <div class="card">
                    <div class="card-header">
                    <span class="close-popup"  >&times;</span>
                        <div class="row">
                            <h5>Upload a JSON or a Zip file</h5>
                        </div>
                    </div>
                    <div class="card-body" style="max-height: 60%">
                        <form action="<?php echo base_url(); ?>/Upload" method="post" class="dropzone" id="fileupload">
                        </form>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</section>



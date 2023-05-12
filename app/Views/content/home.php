<section class="vh-100" id="home">
    <div class="container-fluid">
        <div class="row pt-5 pb-4 justify-content-center">
            <div class="chat-record-container">
                <div class="chat-record-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-5 ">
                                    <h5>Chat Records</h5>
                                    <form action="<?=base_url('/')?>" method="get" id="filter">
                                        <label for="datetime">Filter:</label>
                                        <input type="datetime-local" id="datetime" name="datetime" step="1">
                                        <select name="filterType" id="filterType">
                                            <option>Before</option>
                                            <option>After</option>
                                        </select>
                                    </form>
                                </div>
                            </div>     
                        </div>
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row border-bottom pb-2">
                                    <div class="col-4"><strong>id</strong></div>
                                    <div class="col-1"><strong>Type</strong></div>
                                    <div class="col-2"><strong>Message Count</strong></div>
                                    <div class="col-1"><strong>Rating</strong></div>
                                    <div class="col-2"><strong>Created On</strong></div>
                                </div>

                                <?php if(isset($jsonfiles)) {
                                    foreach($jsonfiles as $index => $row) { ?>
                                        <div class="card-data">
                                            <div class="row pt-2">
                                                <div class="col-4"><a class="record-details" href="#" id="<?=$index?>"><?=$row->id?></a></div>
                                                <div class="col-1"><?=$row->type?></div>
                                                <div class="col-2"><?=$row->messageCount?></div>
                                                <div class="col-1"><?=$row->rating?></div>
                                                <div class="col-2"><?=$row->createdOn?></div>
                                                <div class="col-2">
                                                    <a class="btn btn-primary btn-sm del-btn" href="<?=base_url('Chat?jsonId='.$row->id)?>">Details</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="record-popup">
                                            <div class="record-popup-content">
                                                <div class="card">
                                                    <div class="card-header">
                                                    <span class="close-popup"  >&times;</span>
                                                        <div class="row">
                                                            <h5>Record: <?=$row->id?></h5>
                                                        </div>
                                                    </div>
                                                    <div class="card-body" style="max-height: 60%">
                                                        <div id="type"></div>
                                                            <strong>Type: </strong> <?=$row->type?>
                                                        <div id="pageid"></div> <hr>
                                                            <strong>Page ID: </strong> <?=$row->pageId?>
                                                        <div id="visitor"></div> <hr>
                                                            <strong>Visitor:</strong><br>
                                                            ID: <?=$visitors[$row->id]['id']?> <br>
                                                            City: <?=$visitors[$row->id]['name']?> <br>
                                                            Email: <?=isset($visitors[$row->id]['email']) ? $visitors[$row->id]['email'] : 'N/A'?>
                                                        <div id="location"> <hr>
                                                            <strong>Location:</strong><br>
                                                            Country Code: <?=$locations[$row->id]['countryCode']?><br>
                                                            City: <?=$locations[$row->id]['city']?>
                                                        </div>
                                                        <div id="messageCount"></div> <hr>
                                                            <strong>Message Count: </strong> <?=$row->messageCount?>
                                                        <div id="rating"></div> <hr>
                                                            <strong>Rating: </strong> <?=$row->rating?>
                                                        <div id="createdOn"></div> <hr>
                                                            <strong>Created On: </strong> <?=$row->createdOn?>
                                                        <div id="domain"> <hr>
                                                            <strong>Domain: </strong> <?=$row->domain?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 

                                    <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 

           
        </div>

        <div class="upload-popup">
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



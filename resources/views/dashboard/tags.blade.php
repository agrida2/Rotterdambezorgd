@include('include.dashboard.header')
<body>
<div class="category-table">
    <h1>Tags</h1>
    <span class="delivery-times-hint">Maximaal vier tags selecteren</span>
    <br>
    <div id ="chosenTagsBadges" class="badgesDiv row">
        @foreach($tagsCurrentRestaurant as $tagCurrentRestaurant)
            <h4 class="badgeHead{{$tagCurrentRestaurant->id}}">
                <span class="tag-badge badge badge-pill badge-success">{{$tagCurrentRestaurant->name}}<i id={{$tagCurrentRestaurant->id}} class="delete-tag fas fa-times fa-xs" aria-hidden="true"></i></span>
            </h4>
        @endforeach
    </div>
    <hr>
    <div class="collapse row show" id="tags-overview" style="margin-left: 0px;">
            <table class="table table-striped">
                <thead class="thead-black">
                <tr>
                    <th scope="col">Tag naam</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id = "tagsTableBody">
                    @foreach($tags as $tag)
                    <tr class= {{$tag->id}} id ={{$tag->name}}>
                        <td class={{$tag->id}}><a href="#">{{$tag->name}}</a></td>
                        <td class={{$tag->id}}><i class="plus-icon fas fa-plus" aria-hidden="true"></i></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
</div>

<script>
    $(document)
    .ajaxStart(function () {
        $("tr").css("pointer-events","none");
    })
    .ajaxStop(function () {
        $("tr").css("pointer-events","auto");
    });
    
    $(document).ready(function(){
        //defining all needed global variables
        var chosenTags = new Array();
        var currentRestaurantTagsAmount = '<?php echo count($tagsCurrentRestaurant)?>';
        var currentRestaurantTags = <?php echo json_encode($tagsCurrentRestaurant)?>;
        var allTags = <?php echo json_encode($tags)?>;
        var tagsTable = document.getElementById("tagsTableBody");
        var numberOfAttachedTags = 0;
        toastr.options.timeOut = 1000;
        //get the number of chosen tags
        $.get("tags/chosenTags",function(data, textStatus, jqXHR){
            numberOfAttachedTags = data["tagsCurrentRestaurant"].length;
        });
        $.each(currentRestaurantTags,function(key,value){
            chosenTags.push(value["name"])
        })
        console.log(chosenTags)
        //functionality to deattach a tag
        $(document).on('click',".delete-tag",function(){
            var deletedTagName = $(this).parent().text();
            $.ajax({
                type:"post",
                url: "/dashboard/tags/RemoveTag",
                data: {id:$(this).attr("id"), _token: '{{csrf_token()}}'},
                success: function(data) {
                    $(".badgeHead"+data["deletedTagId"]).hide();
                    toastr.success(data["status"]);
                    currentRestaurantTags = data["tags"];
                    numberOfAttachedTags--;
                    removeTag(deletedTagName,chosenTags);
                    
                },
                error: function (data, textStatus, errorThrown) {
                    toastr.error(data["status"]);
                },
            });
        })
        //functionality to add a tag
        $("tr").click(function(){
            if($.inArray($(this).attr("id"),chosenTags) != -1){
                toastr.error("Tag is al door u geselecteerd");
                console.log("first if");

            }
            else{
                if(numberOfAttachedTags >= 4){
                    toastr["warning"]("Het maximale aantal tags is bereikt");
                }
                else{
                    var chosenTagName = $(this).attr("id");
                    $.ajax({
                        type: "post",
                        url: '/dashboard/tags/addTagToRestaurant',
                        data: { tagId: $(this).attr("class"), _token: '{{csrf_token()}}' },
                        success: function (data) {
                            // chosenTags.push($(this).attr("class"));
                            chosenTags.push(chosenTagName);
                            toastr.success(data["status"]).delay(1000);
                            $("#chosenTagsBadges").append(data["tagBadge"]);
                            numberOfAttachedTags++;
                        },
                        error: function (data, textStatus, errorThrown) {
                            console.log(data);
                            toastr.error(data["status"]);
                        },
                    });
                }
            }
        });
        //check if tag is already chosen
        function isChosen(tagId){
            for(var i = 0;i<chosenTags.length;i++){
                if(chosenTags[i]==tagId){
                    return true;
                }
            }
        };
        //check if tag is chosen using data from db
        function tagIsChosen(arrayToCheck,tagId){
            for(var i = 0; i<arrayToCheck.length;i++){
                if(tagId == arrayToCheck[i]['tag_id']){
                    return true;
                }
            }
        };
        //delete chosen tag from tags array
        function removeTag(tagName,TagsArray){
            TagsArray.splice(TagsArray.indexOf(tagName),1);
        }
         })
</script>
</body>

@include('include.dashboard.footer')

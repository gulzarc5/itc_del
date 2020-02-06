
/////////////////////////////// Add More Size Section
var sizeDivCount = 1;
function addMoreSize() {
    var sizeHtml = '<div id="more_size'+sizeDivCount+'">'+
        '<label class="col-sm-12 control-label">Size</label>'+
            '<div class="col-sm-2" style="width:150px;">'+
                '<input type="text" name="swidth[]" id="swidth" class="form-control" placeholder="Width" required="">'+
            '</div>'+
            '<div class="col-sm-2" style="width:auto;line-height: 40px;">X</div>'+
            '<div class="col-sm-2" style="width:150px;">'+
                '<input type="text" name="sheigth[]" id="sheigth" class="form-control" placeholder="Height" required="">'+
            '</div>'+
            '<div class="col-sm-2">'+
                '<input type="text" name="displaysize[]" id="displaysize" class="form-control" placeholder="Display Size" required="">'+
           '</div>'+
            '<button type="button" style="border:0;background:transparent" onclick="removeSizeDiv('+sizeDivCount+')"><i class="fa fa-trash" aria-hidden="false" style="font-size: 32px;color: #ff7600f0;"></i></button>'+
        '</div>';
    $("#size-div").append(sizeHtml);
    sizeDivCount++;
}

function removeSizeDiv(id) {
    $("#more_size"+id).remove();
    sizeDivCount--;
}


/////////////////////////////// Add More Option Section 
var optionDivCount = 2 ;
function moreOption() {
    var optionStep = numToString(optionDivCount);
    var optionHtml = '<div class="col-md-6 col-sm-12 col-xs-12 mb-3" id="more-option'+optionDivCount+'">'+
                '<label for="name">'+optionStep+' Option Name</label><br><input type="text" class="form-control" name="option[]"  placeholder="Enter Product name" id="name" style="width:80%;float:left" required>'+
                '<button type="button" onclick="removeOption('+optionDivCount+')" style="border:0;background:transparent;float:right"><i class="fa fa-trash" aria-hidden="false" style="font-size: 32px;color: #ff7600f0;"></i></button>'+
            '</div>';
        $("#option-div").append(optionHtml);
    optionDivCount++;
}

function removeOption(id) {
    $("#more-option"+id).remove();
    optionDivCount--;
}

function numToString(num) {
    if (num == '1') {
        return "First";
    }else if (num == '2') {
        return "Second";
    }else if (num == '3') {
        return "Third";
    }else if (num == '4') {
        return "Fourtn";
    }else if (num == '5') {
        return "Fifth";
    }else if (num == '6') {
        return "Sixth";
    }else if (num == '7') {
        return "Seventh";
    }else if (num == '8') {
        return "Eighth";
    }else if (num == '9') {
        return "Ninth";
    }else if (num == '10') {
        return "Tenth";
    }else if (num == '11') {
        return "Eleventh";
    }else if (num == '12') {
        return "Twelveth";
    }else if (num == '13') {
        return "Thirteenth";
    }else if (num == '14') {
        return "Fourthinth";
    }else if (num == '15') {
        return "Fiftinth";
    } else {
        return "Sixtinth";
    }
}


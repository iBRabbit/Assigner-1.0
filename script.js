let checkAllRadioGroupAsg = document.querySelector("#check-all");
let rowCheckRadioGroupAsg = document.querySelectorAll("#row-check");
let checkedAllGroupAsg = false;

function checkAllGroupMembersAsg () {
    if (!checkedAllGroupAsg) {
        for (let i = 0; i < rowCheckRadioGroupAsg.length; i++) rowCheckRadioGroupAsg[i].setAttribute("checked", "");
        checkedAllGroupAsg = true;
    } else {
        checkedAllGroupAsg = false;
        for (let i = 0; i < rowCheckRadioGroupAsg.length; i++) rowCheckRadioGroupAsg[i].removeAttribute("checked");
    }
}

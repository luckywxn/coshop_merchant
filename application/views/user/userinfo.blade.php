<script src="/static/common/js/custom.js"></script>
<div class="bjui-pageContent">
    <form id="userinfoform" action="/user/userEditJson/" class="datagrid-edit-form" data-toggle="validate" data-data-type="json">
        <input type="hidden" name="id" value="{{$sysno}}">
        <input type="hidden" name="role" value="{{$role}}">
        <div class="bjui-row col-3">
            <label class="row-label">商户编号</label>
            <div class="row-input">
            <input type="text" name="merchant_no" value="{{$merchant_no}}" readonly>
            </div>

            <label class="row-label">商户名称</label>
            <div class="row-input">
                <input type="text" name="merchant_name" value="{{$merchant_name}}" readonly>
            </div>

            <label class="row-label">联系人</label>
            <div class="row-input">
                <input type="text" name="contact_name" value="{{$contact_name}}" readonly>
            </div>

            <label class="row-label">联系电话</label>
            <div class="row-input">
                <input type="text" name="contact_tel" value="{{$contact_tel}}" readonly>
            </div>

            <label class="row-label">联系邮箱</label>
            <div class="row-input">
                <input type="text" name="contact_email" value="{{$contact_email}}" readonly>
            </div>

            <label class="row-label">联系地址</label>
            <div class="row-input">
                <input type="text" name="contact_address" value="{{$contact_address}}" data-rule="chinese" readonly>
            </div>


            <label class="row-label">备注</label>
            <div class="row-input">
                <textarea type="text" name="memo" >{{$memo}}</textarea>
            </div>
        </div>
    </form>
</div>
<script>
    $("#userinfosave").click(function (){
        BJUI.ajax('ajaxform', {
            url: '/user/userEditJson/',
            form: $.CurrentNavtab.find('#userinfoform'),
            validate: true,
            loadingmask: true,
            okCallback: function (json, options) {
                BJUI.navtab('closeCurrentTab', '');
            }
        });
    })
</script>
<script src="/static/common/js/custom.js"></script>
<div class="bjui-pageContent">
    <div class="bs-example">
        <form action="{{$action}}" class="datagrid-edit-form" data-toggle="validate" data-data-type="json">
            <input type="hidden" name="sysno" value="{{$good['sysno']}}">
            <div class="bjui-row col-1">
                <label class="row-label">商品分类</label>
                <div class="row-input required">
                    <select name="classify_sysno" data-rule="required" data-live-search="true" data-toggle="selectpicker" data-size="5" data-width="100%">
                        <option value="">请选择</option>
                        @foreach($goodsClassify as $item)
                            <option value="{{$item['sysno']}}"  @if($item['sysno'] == $good['classify_sysno']) selected @endif >{{$item['classifyname']}}</option>
                        @endforeach
                    </select>
                </div>

                <label class="row-label">商品编号</label>
                <div class="row-input">
                    <input type="text" name="goodsno" value="{{$good['goodsno']}}">
                </div>

                <label class="row-label">商品名称</label>
                <div class="row-input required">
                    <input type="text" name="goodsname" value="{{$good['goodsname']}}" data-rule='required' >
                </div>

                <label class="row-label">商品价格</label>
                <div class="row-input required">
                    <input type="text" name="price" value="{{$good['price']}}"  data-rule='required'>
                </div>

                <label class="row-label">商品介绍</label>
                <div class="row-input">
                    <input type="text" name="introduce" value="{{$good['introduce']}}">
                </div>

                <label class="row-label">状态</label>
                <div class="row-input required">
                    <input type="radio" name="status"  data-toggle="icheck" value="1" data-rule="checked" data-label="启用&nbsp;&nbsp;" @if( !$status || $status ==1) checked @endif>
                    <input type="radio" name="status"  data-toggle="icheck" value="2" data-label="停用" @if($status ==2) checked @endif>
                </div>

            </div>
        </form>
    </div>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>
<select name='searchfield'  class='form-control  input-sm select-md'   style="width:95px;padding:0 5px;"  >

                <option value='title' {if $_GPC['searchfield']=='title'}selected{/if}>商品名称</option>
                <option value='keywords' {if $_GPC['searchfield']=='keywords'}selected{/if}>商品关键字</option>

                <option value='id' {if $_GPC['searchfield']=='id'}selected{/if}>商品ID</option>
                <option value='jd_vop_sku' {if $_GPC['searchfield']=='jd_vop_sku'}selected{/if}>京东SKU</option>

                <option value='goodssn' {if $_GPC['searchfield']=='goodssn'}selected{/if}>商品编码</option>
                <option value='productsn' {if $_GPC['searchfield']=='productsn'}selected{/if}>商品条码</option>

            </select>
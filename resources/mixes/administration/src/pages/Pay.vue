<script>
    import expandRow from './TableExpand.vue';
    import injection from '../helpers/injection';

    export default {
        beforeRouteEnter(to, from, next) {
            next(() => {
                injection.sidebar.active('setting');
            });
        },
        components: {
            expandRow,
        },
        data() {
            return {
                action: 'http://pay.ibenchu.xyz:8080/upload',
                alipayForm: {
                    alipay_enabled: true,
                    private_key: '',
                    app_id: '',
                    public_key: '',
                },
                alipayRules: {
                    app_id: [
                        {
                            message: 'APP_ID不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                    private_key: [
                        {
                            message: '私钥不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                    public_key: [
                        {
                            message: '公钥不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                },
                loading: false,
                managementSearch: '',
                orderColumns: [
                    {
                        type: 'expand',
                        render(h, params) {
                            return h(expandRow, {
                                props: {
                                    row: params.row,
                                },
                            });
                        },
                        width: 50,
                    },
                    {
                        align: 'center',
                        key: 'num',
                        title: '支付订单号',
                        width: 200,
                    },
                    {
                        align: 'center',
                        key: 'count',
                        title: '金额(元)',
                        width: 200,
                    },
                    {
                        align: 'center',
                        key: 'status',
                        title: '状态',
                        width: 200,
                    },
                    {
                        key: 'createTime',
                        title: '时间',
                    },
                ],
                orderData: [
                    {
                        count: '99.00',
                        createTime: '2017-6-16 16:12:25',
                        num: 222222224566,
                        payStyle: '微信支付',
                        status: '代付款',
                        transactionCtx: '长安通充值长安通充值',
                        transactionNum: 78654342367878,
                    },
                    {
                        count: '99.00',
                        createTime: '2017-6-16 16:12:25',
                        num: 222222226466,
                        payStyle: '微信支付',
                        status: '代付款',
                        transactionCtx: '长安通充值长安通充值',
                        transactionNum: 78654342367878,
                    },
                    {
                        count: '99.00',
                        createTime: '2017-6-16 16:12:25',
                        num: 22222222,
                        payStyle: '微信支付',
                        status: '代付款',
                        transactionCtx: '长安通充值长安通充值',
                        transactionNum: 78654342367878,
                    },
                ],
                searchList: [
                    {
                        label: '店铺名称',
                        value: '店铺名称',
                    },
                    {
                        label: '商品名称',
                        value: '商品名称',
                    },
                    {
                        label: '商品分类',
                        value: '商品分类',
                    },
                ],
                self: this,
                unionPay: {
                    enabled: true,
                    key: '',
                    mer_id: '',
                },
                unionPayRules: {
                    mer_id: [
                        {
                            message: 'ID不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                },
                weChatForm: {
                    app_id: '',
                    app_secret: '',
                    cert_path: '',
                    enabled: true,
                    key: '',
                    mch_id: '',
                },
                weChatRules: {
                    app_id: [
                        {
                            message: 'APP_ID不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                    app_secret: [
                        {
                            message: 'APPSECRET不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                },
            };
        },
        methods: {
            alipaySubmit() {
                const self = this;
                self.loading = true;
                self.$refs.alipayForm.validate(valid => {
                    if (valid) {
                        self.$http.post('http://pay.ibenchu.xyz:8080/api/multipay/alipay/set', self.alipayForm).then(() => {
                            self.$notice.open({
                                title: injection.trans('alipay.setting.success'),
                            });
                        }).finally(() => {
                            self.loading = false;
                        });
                    } else {
                        self.loading = false;
                        self.$notice.error({
                            title: injection.trans('alipay.setting.fail'),
                        });
                    }
                });
            },
            unionPaySubmit() {
                const self = this;
                self.loading = true;
                self.$refs.unionPay.validate(valid => {
                    if (valid) {
                        self.$http.post('http://pay.ibenchu.xyz:8080/api/multipay/union/set', self.unionPay).then(() => {
                            self.$notice.open({
                                title: injection.trans('union.setting.success'),
                            });
                        }).finally(() => {
                            self.loading = false;
                        });
                    } else {
                        self.loading = false;
                        self.$notice.error({
                            title: injection.trans('union.setting.fail'),
                        });
                    }
                });
            },
            weChatSubmit() {
                const self = this;
                self.loading = true;
                self.$refs.weChatForm.validate(valid => {
                    if (valid) {
                        self.$http.post('http://pay.ibenchu.xyz:8080/api/multipay/wechat/set', self.weChatForm).then(() => {
                            self.$notice.open({
                                title: injection.trans('weChat.setting.success'),
                            });
                        }).finally(() => {
                            self.loading = false;
                        });
                    } else {
                        self.loading = false;
                        self.$notice.error({
                            title: injection.trans('weChat.setting.fail'),
                        });
                    }
                });
            },
        },
    };
</script>
<template>
    <div class="setting-wrap">
        <div class="pay-wrap">
            <tabs value="name1">
                <tab-pane label="参数配置" name="name1">
                    <card :bordered="false">
                        <tabs type="card">
                            <tab-pane label="支付宝">
                                <i-form :label-width="200" :model="alipayForm" ref="alipayForm" :rules="alipayRules">
                                    <row>
                                        <i-col span="12">
                                            <form-item label="APP_ID" prop="app_id">
                                                <i-input v-model="alipayForm.app_id"></i-input>
                                                <a href="">点击此处获取</a>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="公钥" prop="public_key">
                                                <i-input v-model="alipayForm.public_key"></i-input>
                                                <a href="">点击此处获取</a>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="私钥" prop="private_key">
                                                <i-input v-model="alipayForm.private_key"></i-input>
                                                <a href="">点击此处获取</a>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="是否开启">
                                                <i-switch size="large" v-model="alipayForm.alipay_enabled">
                                                    <span slot="open">开启</span>
                                                    <span slot="close">关闭</span>
                                                </i-switch>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item>
                                                <i-button :loading="loading" type="primary"
                                                          @click.native="alipaySubmit">
                                                    <span v-if="!loading">确认提交</span>
                                                    <span v-else>正在提交…</span>
                                                </i-button>
                                            </form-item>
                                        </i-col>
                                    </row>
                                </i-form>
                            </tab-pane>
                            <tab-pane label="微信">
                                <i-form :label-width="200" :model="weChatForm" ref="weChatForm" :rules="weChatRules">
                                    <row>
                                        <i-col span="12">
                                            <form-item label="APP_ID" prop="app_id">
                                                <i-input v-model="weChatForm.app_id"></i-input>
                                                <p class="tip">
                                                    应用ID ，在微信给公众平台“开发者中心”栏目可以查看到
                                                </p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="APPSECRET" prop="app_secret">
                                                <i-input v-model="weChatForm.app_secret"></i-input>
                                                <p class="tip">
                                                    在微信公众平台中“开发者中心”栏目可以产看到
                                                </p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="商户ID">
                                                <i-input v-model="weChatForm.mch_id"></i-input>
                                                <p class="tip">
                                                    请输入商户账号，如果没有
                                                    <a href="">点击此处获取</a>
                                                </p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="商户密钥">
                                                <i-input v-model="weChatForm.key"></i-input>
                                                <p class="tip">
                                                    API密钥，在微信商户平台中“账户设置”-“账户安全”-“设置API密钥”
                                                </p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="18">
                                            <form-item label="上传文件">
                                                <upload :action="action">
                                                    <i-button type="ghost">+上传</i-button>
                                                </upload>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="是否开启">
                                                <i-switch size="large" v-model="weChatForm.enabled">
                                                    <span slot="open">开启</span>
                                                    <span slot="close">关闭</span>
                                                </i-switch>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item>
                                                <i-button :loading="loading" type="primary"
                                                          @click.native="weChatSubmit">
                                                    <span v-if="!loading">确认提交</span>
                                                    <span v-else>正在提交…</span>
                                                </i-button>
                                            </form-item>
                                        </i-col>
                                    </row>
                                </i-form>
                            </tab-pane>
                            <tab-pane label="银联">
                                <i-form :label-width="200" :model="unionPay" ref="unionPay" :rules="unionPayRules">
                                    <row>
                                        <i-col span="12">
                                            <form-item label="ID" prop="mer_id">
                                                <i-input v-model="unionPay.mer_id"></i-input>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="18">
                                            <form-item label="上传文件">
                                                <upload :action="action">
                                                    <i-button type="ghost">+上传</i-button>
                                                </upload>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="密钥" prop="key">
                                                <i-input v-model="unionPay.key"></i-input>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="是否开启">
                                                <i-switch size="large" v-model="unionPay.enabled">
                                                    <span slot="open">开启</span>
                                                    <span slot="close">关闭</span>
                                                </i-switch>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item>
                                                <i-button :loading="loading" type="primary"
                                                          @click.native="unionPaySubmit">
                                                    <span v-if="!loading">确认提交</span>
                                                    <span v-else>正在提交…</span>
                                                </i-button>
                                            </form-item>
                                        </i-col>
                                    </row>
                                </i-form>
                            </tab-pane>
                        </Tabs>
                    </card>
                </tab-pane>
                <tab-pane label="支付订单" name="name2">
                    <card :bordered="false">
                        <div class="order-search-module clearfix">
                            <div class="order-money-content">
                                <div class="select-content">
                                    <ul class="clearfix">
                                        <li>
                                            成交时间
                                            <date-picker type="date" placeholder="选择日期"
                                                         style="width: 124px"></date-picker>
                                            -
                                            <date-picker type="date" placeholder="选择日期"
                                                         style="width: 124px"></date-picker>
                                        </li>
                                        <li class="store-body-header-right">
                                            <i-input v-model="applicationWord" placeholder="请输入关键词进行搜索">
                                                <i-select v-model="managementSearch" slot="prepend" style="width: 100px;">
                                                    <i-option v-for="item in searchList"
                                                              :value="item.value">{{ item.label }}</i-option>
                                                </i-select>
                                                <i-button slot="append" type="primary">搜索</i-button>
                                            </i-input>
                                        </li>
                                    </ul>
                                </div>
                                <!-- <div class="page">
                                     <page :total="100" show-elevator></page>
                                 </div>-->
                            </div>
                        </div>
                        <i-table class="order-table"
                                 :columns="orderColumns"
                                 :context="self"
                                 :data="orderData"
                                 ref="orderList">
                        </i-table>
                    </card>
                </tab-pane>
            </tabs>
        </div>
    </div>
</template>
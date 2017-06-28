<script>
    import expandRow from './TableExpand.vue';
    import injection from '../helpers/injection';

    export default {
        beforeRouteEnter(to, from, next) {
            injection.loading.start();
            injection.http.get('http://pay.ibenchu.xyz:8080/api/multipay/order').then(response => {
                const data = response.data.data;
                next(vm => {
                    vm.orderData = data.data;
                    vm.page.total = data.total;
                    vm.page.per_page = data.per_page;
                    vm.page.last_page = data.last_page;
                    vm.page.to = data.to;
                    injection.loading.finish();
                    injection.sidebar.active('setting');
                });
            });
        },
        components: {
            expandRow,
        },
        data() {
            const self = this;
            const reg1 = /^\d{10}$/;
            const reg2 = /^\d{15}$/;
            const reg3 = /^\d{16}$/;
            const reg4 = /^(?=.*\d+)(?=.*[a-zA-Z]+)[\da-zA-Z]{18}$/;
            const reg5 = /^(?!^\d+$)(?!^[a-zA-Z]+$)[\da-zA-Z]{32}$/;
            const validatorMch = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('商户ID不能为空'));
                } else if (!reg1.test(value)) {
                    callback(new Error('商户ID必须为10位数字'));
                } else {
                    callback();
                }
            };
            const validatorUnion = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('ID不能为空'));
                } else if (!reg2.test(value)) {
                    callback(new Error('ID必须为15位数字'));
                } else {
                    callback();
                }
            };
            const validatorAlipay = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('APP_ID不能为空'));
                } else if (!reg3.test(value)) {
                    callback(new Error('APP_ID必须为16位数字'));
                } else {
                    callback();
                }
            };
            const validatorWechatId = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('APP_ID不能为空'));
                } else if (!reg4.test(value)) {
                    callback(new Error('APP_ID必须为18位数字,字母组成的字符串(不含特殊字符)'));
                } else {
                    callback();
                }
            };
            const validatorAppSecret = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('APP_SECRET不能为空'));
                } else if (!reg5.test(value)) {
                    callback(new Error('APP_SECRET必须为32位数字,字母组成的字符串(不含特殊字符)'));
                } else {
                    callback();
                }
            };
            const validatorWeChatKey = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('商户密钥不能为空'));
                } else if (!reg5.test(value)) {
                    callback(new Error('商户密钥必须为32位数字,字母组成的字符串(不含特殊字符)'));
                } else {
                    callback();
                }
            };
            const validatorCert = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('证书_cert不能为空'));
                } else {
                    callback();
                }
            };
            const validatorCertKey = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('证书_Key不能为空'));
                } else {
                    callback();
                }
            };
            const validatorCertUnion = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('证书_cert不能为空'));
                } else {
                    callback();
                }
            };
            return {
                actionCert: 'http://pay.ibenchu.xyz:8080/api/multipay/upload?driver=wechat&certname=cert',
                actionKey: 'http://pay.ibenchu.xyz:8080/api/multipay/upload?driver=wechat&certname=cert_key',
                actionUnionCert: 'http://pay.ibenchu.xyz:8080/api/multipay/upload?driver=union&certname=cert',
                alipayForm: {
                    alipay_enabled: true,
                    alipay_key: '',
                    private_key: '',
                    app_id: '',
                    public_key: '',
                },
                alipayRules: {
                    alipay_key: [
                        {
                            message: '支付宝公钥不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                    app_id: [
                        {
                            required: true,
                            trigger: 'change',
                            validator: validatorAlipay,
                        },
                    ],
                    private_key: [
                        {
                            message: '应用私钥不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                    public_key: [
                        {
                            message: '应用公钥不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                },
                filterSearch: {
                    end: '',
                    search: '',
                    start: '',
                },
                loading: false,
                messageCert: '',
                messageKey: '',
                messageUnion: '',
                options1: {
                    disabledDate(date) {
                        return date && date.valueOf() > Date.now();
                    },
                },
                options2: {
                    disabledDate(date) {
                        return date && date.valueOf() < self.getOrderBegin();
                    },
                },
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
                        key: 'trade_no',
                        title: '支付订单号',
                        width: 300,
                    },
                    {
                        align: 'center',
                        key: 'total_amount',
                        title: '金额(元)',
                        width: 200,
                    },
                    {
                        align: 'center',
                        key: 'trade_status',
                        title: '状态',
                        width: 200,
                    },
                    {
                        key: 'created_at',
                        title: '时间',
                    },
                ],
                orderData: [
                    {
                        total_amount: '',
                        created_at: '',
                        out_trade_no: '',
                        payment: '',
                        trade_status: '',
                        subject: '',
                        trade_no: '',
                    },
                ],
                page: {
                    from: 1,
                    last_page: 0,
                    per_page: 0,
                    to: 0,
                    total: 0,
                },
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
                    cert: '',
                    enabled: true,
                    key: '',
                    mer_id: '',
                },
                unionPayRules: {
                    cert: [
                        {
                            required: true,
                            trigger: 'blur',
                            validator: validatorCertUnion,
                        },
                    ],
                    key: [
                        {
                            message: '密钥不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                    mer_id: [
                        {
                            required: true,
                            trigger: 'change',
                            validator: validatorUnion,
                        },
                    ],
                },
                weChatForm: {
                    app_id: '',
                    app_secret: '',
                    cert: '',
                    cert_key: '',
                    enabled: true,
                    key: '',
                    mch_id: '',
                },
                weChatRules: {
                    app_id: [
                        {
                            required: true,
                            trigger: 'change',
                            validator: validatorWechatId,
                        },
                    ],
                    app_secret: [
                        {
                            required: true,
                            trigger: 'change',
                            validator: validatorAppSecret,
                        },
                    ],
                    cert: [
                        {
                            required: true,
                            trigger: 'blur',
                            validator: validatorCert,
                        },
                    ],
                    cert_key: [
                        {
                            required: true,
                            trigger: 'blur',
                            validator: validatorCertKey,
                        },
                    ],
                    key: [
                        {
                            required: true,
                            trigger: 'change',
                            validator: validatorWeChatKey,
                        },
                    ],
                    mch_id: [
                        {
                            required: true,
                            trigger: 'change',
                            validator: validatorMch,
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
            changePage(page) {
                const self = this;
                let filterSearchParam;
                if (this.filterSearch.start === '' && this.filterSearch.end === '' && this.filterSearch.search === '') {
                    filterSearchParam = {
                        end: '',
                        search: '',
                        start: '',
                    };
                } else if (this.filterSearch.start === '' && this.filterSearch.end === '' && this.filterSearch.search !== '') {
                    filterSearchParam = {
                        end: '',
                        search: this.filterSearch.search,
                        start: '',
                    };
                } else if (this.filterSearch.start === '' && this.filterSearch.end !== '' && this.filterSearch.search === '') {
                    filterSearchParam = {
                        end: new Date(this.filterSearch.end).toLocaleString(),
                        search: '',
                        start: '',
                    };
                } else if (this.filterSearch.start !== '' && this.filterSearch.end === '' && this.filterSearch.search === '') {
                    filterSearchParam = {
                        end: '',
                        search: '',
                        start: new Date(this.filterSearch.start).toLocaleString(),
                    };
                } else if (this.filterSearch.start === '' && this.filterSearch.end !== '' && this.filterSearch.search !== '') {
                    filterSearchParam = {
                        end: new Date(this.filterSearch.end).toLocaleString(),
                        search: this.filterSearch.search,
                        start: '',
                    };
                } else if (this.filterSearch.start !== '' && this.filterSearch.end === '' && this.filterSearch.search !== '') {
                    filterSearchParam = {
                        end: '',
                        search: this.filterSearch.search,
                        start: new Date(this.filterSearch.start).toLocaleString(),
                    };
                } else if (this.filterSearch.start !== '' && this.filterSearch.end !== '' && this.filterSearch.search === '') {
                    filterSearchParam = {
                        end: new Date(this.filterSearch.end).toLocaleString(),
                        search: '',
                        start: new Date(this.filterSearch.start).toLocaleString(),
                    };
                } else {
                    filterSearchParam = {
                        end: new Date(this.filterSearch.end).toLocaleString(),
                        search: this.filterSearch.search,
                        start: new Date(this.filterSearch.start).toLocaleString(),
                    };
                }
                self.$http.post(`http://pay.ibenchu.xyz:8080/api/multipay/order?page=${page}`, filterSearchParam).then(response => {
                    const data = response.data.data;
                    this.orderData = data.data;
                    this.page.total = data.total;
                    this.page.per_page = data.per_page;
                    this.page.last_page = data.last_page;
                    this.page.to = data.to;
                }).finally(() => {
                    self.loading = false;
                });
            },
            getOrderBegin() {
                return Date.parse(this.filterSearch.start);
            },
            queryMessage() {
                const self = this;
                self.$http.post('http://pay.ibenchu.xyz:8080/api/query').then(response => {
                    console.log(response);
                }).finally(() => {
                    self.loading = false;
                });
            },
            search() {
                const self = this;
                let filterSearchParam;
                if (this.filterSearch.start === '' && this.filterSearch.end === '' && this.filterSearch.search === '') {
                    filterSearchParam = {
                        end: '',
                        search: '',
                        start: '',
                    };
                } else if (this.filterSearch.start === '' && this.filterSearch.end === '' && this.filterSearch.search !== '') {
                    filterSearchParam = {
                        end: '',
                        search: this.filterSearch.search,
                        start: '',
                    };
                } else if (this.filterSearch.start === '' && this.filterSearch.end !== '' && this.filterSearch.search === '') {
                    filterSearchParam = {
                        end: new Date(this.filterSearch.end).toLocaleString(),
                        search: '',
                        start: '',
                    };
                } else if (this.filterSearch.start !== '' && this.filterSearch.end === '' && this.filterSearch.search === '') {
                    filterSearchParam = {
                        end: '',
                        search: '',
                        start: new Date(this.filterSearch.start).toLocaleString(),
                    };
                } else if (this.filterSearch.start === '' && this.filterSearch.end !== '' && this.filterSearch.search !== '') {
                    filterSearchParam = {
                        end: new Date(this.filterSearch.end).toLocaleString(),
                        search: this.filterSearch.search,
                        start: '',
                    };
                } else if (this.filterSearch.start !== '' && this.filterSearch.end === '' && this.filterSearch.search !== '') {
                    filterSearchParam = {
                        end: '',
                        search: this.filterSearch.search,
                        start: new Date(this.filterSearch.start).toLocaleString(),
                    };
                } else if (this.filterSearch.start !== '' && this.filterSearch.end !== '' && this.filterSearch.search === '') {
                    filterSearchParam = {
                        end: new Date(this.filterSearch.end).toLocaleString(),
                        search: '',
                        start: new Date(this.filterSearch.start).toLocaleString(),
                    };
                } else {
                    filterSearchParam = {
                        end: new Date(this.filterSearch.end).toLocaleString(),
                        search: this.filterSearch.search,
                        start: new Date(this.filterSearch.start).toLocaleString(),
                    };
                }
                self.$http.post('http://pay.ibenchu.xyz:8080/api/multipay/order', filterSearchParam).then(response => {
                    const data = response.data.data;
                    this.orderData = data.data;
                    this.page.total = data.total;
                    this.page.per_page = data.per_page;
                    this.page.last_page = data.last_page;
                    this.page.to = data.to;
                }).finally(() => {
                    self.loading = false;
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
            uploadBefore() {
                injection.loading.start();
            },
            uploadCertError() {
                this.$notice.warning({
                    title: '请上传pem格式证书',
                });
                this.messageCert = '';
            },
            uploadCertKeyError() {
                this.$notice.warning({
                    title: '请上传pem格式证书',
                });
                this.messageKey = '';
            },
            uploadCertSuccess(data) {
                const self = this;
                injection.loading.finish();
                self.$notice.open({
                    title: '证书_cert上传成功',
                });
                self.messageCert = '已上传';
                self.weChatForm.cert = data;
            },
            uploadCertKeySuccess(data) {
                const self = this;
                injection.loading.finish();
                self.$notice.open({
                    title: '证书_key上传成功',
                });
                self.messageKey = '已上传';
                self.weChatForm.cert_key = data;
            },
            uploadUnionError() {
                this.$notice.warning({
                    title: '请上传pfx格式证书',
                });
                this.messageUnion = '';
            },
            uploadUnionSuccess(data) {
                const self = this;
                injection.loading.finish();
                self.$notice.open({
                    title: '证书_cert上传成功',
                });
                self.messageUnion = '已上传';
                self.unionPay.cert = data;
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
                                        <i-col span="14">
                                            <form-item label="APP_ID" prop="app_id">
                                                <i-input v-model="alipayForm.app_id"></i-input>
                                                <a @click="queryMessage">点击此处获取</a>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item label="应用公钥" prop="public_key">
                                                <i-input v-model="alipayForm.public_key"></i-input>
                                                <a @click="queryMessage">点击此处获取</a>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item label="应用私钥" prop="private_key">
                                                <i-input v-model="alipayForm.private_key"></i-input>
                                                <a @click="queryMessage">点击此处获取</a>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item label="支付宝公钥" prop="alipay_key">
                                                <i-input v-model="alipayForm.alipay_key"></i-input>
                                                <a @click="queryMessage">点击此处获取</a>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item label="是否开启">
                                                <i-switch size="large" v-model="alipayForm.alipay_enabled">
                                                    <span slot="open">开启</span>
                                                    <span slot="close">关闭</span>
                                                </i-switch>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item>
                                                <i-button class="submit-pay-btn" :loading="loading" type="primary"
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
                                        <i-col span="14">
                                            <form-item label="APP_ID" prop="app_id">
                                                <i-input v-model="weChatForm.app_id"></i-input>
                                                <p class="tip">
                                                    应用ID ，在微信给公众平台“开发者中心”栏目可以查看到
                                                </p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item label="APP_SECRET" prop="app_secret">
                                                <i-input v-model="weChatForm.app_secret"></i-input>
                                                <p class="tip">
                                                    在微信公众平台中“开发者中心”栏目可以产看到
                                                </p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item label="商户ID" prop="mch_id">
                                                <i-input v-model="weChatForm.mch_id"></i-input>
                                                <p class="tip">
                                                    请输入商户账号，如果没有
                                                    <a>点击此处获取</a>
                                                </p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item label="商户密钥" prop="key">
                                                <i-input v-model="weChatForm.key"></i-input>
                                                <p class="tip">
                                                    API密钥，在微信商户平台中“账户设置”-“账户安全”-“设置API密钥”
                                                </p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="18">
                                            <form-item label="证书_cert" prop="cert">
                                                <upload :action="actionCert"
                                                        :before-upload="uploadBefore"
                                                        :format="['pem']"
                                                        :headers="{
                                                            Authorization: `Bearer ${$store.state.token.access_token}`
                                                        }"
                                                        :max-size="2048"
                                                        name="cert"
                                                        :on-error="uploadError"
                                                        :on-format-error="uploadCertError"
                                                        :on-success="uploadCertSuccess"
                                                        ref="upload"
                                                        :show-upload-list="false">
                                                    <i-button type="ghost">+上传</i-button>
                                                    <span>{{ messageCert }}</span>
                                                </upload>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="18">
                                            <form-item label="证书_Key"  prop="cert_key">
                                                <upload :action="actionKey"
                                                        :before-upload="uploadBefore"
                                                        :format="['pem']"
                                                        :headers="{
                                                            Authorization: `Bearer ${$store.state.token.access_token}`
                                                        }"
                                                        :max-size="2048"
                                                        name="cert_key"
                                                        :on-error="uploadError"
                                                        :on-format-error="uploadCertKeyError"
                                                        :on-success="uploadCertKeySuccess"
                                                        ref="upload"
                                                        :show-upload-list="false">
                                                    <i-button type="ghost">+上传</i-button>
                                                    <span>{{ messageKey }}</span>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item label="是否开启">
                                                <i-switch size="large" v-model="weChatForm.enabled">
                                                    <span slot="open">开启</span>
                                                    <span slot="close">关闭</span>
                                                </i-switch>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item>
                                                <i-button class="submit-pay-btn" :loading="loading" type="primary"
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
                                        <i-col span="14">
                                            <form-item label="ID" prop="mer_id">
                                                <i-input v-model="unionPay.mer_id"></i-input>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="18">
                                            <form-item label="证书_cert" prop="cert">
                                                <upload :action="actionUnionCert"
                                                        :before-upload="uploadBefore"
                                                        :format="['pfx']"
                                                        :headers="{
                                                            Authorization: `Bearer ${$store.state.token.access_token}`
                                                        }"
                                                        :max-size="2048"
                                                        name="cert"
                                                        :on-error="uploadError"
                                                        :on-format-error="uploadUnionError"
                                                        :on-success="uploadUnionSuccess"
                                                        ref="upload"
                                                        :show-upload-list="false">
                                                    <i-button type="ghost">+上传</i-button>
                                                    <span>{{ messageUnion }}</span>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item label="密钥" prop="key">
                                                <i-input v-model="unionPay.key"></i-input>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item label="是否开启">
                                                <i-switch size="large" v-model="unionPay.enabled">
                                                    <span slot="open">开启</span>
                                                    <span slot="close">关闭</span>
                                                </i-switch>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="14">
                                            <form-item>
                                                <i-button class="submit-pay-btn" :loading="loading" type="primary"
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
                                            <date-picker format="yyyy-MM-dd" placeholder="开始时间" type="date"
                                                         v-model="filterSearch.start" :options="options1"
                                                         style="width: 124px"></date-picker>
                                            -
                                            <date-picker format="yyyy-MM-dd" placeholder="结束时间" type="date"
                                                         v-model="filterSearch.end" :options="options2"
                                                         style="width: 124px"></date-picker>
                                        </li>
                                        <li class="store-body-header-right">
                                            <i-input v-model="filterSearch.search" placeholder="请输入关键词进行搜索">
                                                <span slot="prepend" style="width: 100px;">商户单号</span>
                                                <i-button slot="append" type="primary" @click.native="search">搜索</i-button>
                                            </i-input>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <i-table class="order-table"
                                 :columns="orderColumns"
                                 :context="self"
                                 :data="orderData"
                                 ref="orderList">
                        </i-table>
                        <div class="page">
                            <page :current="1"
                                  :total="page.total"
                                  :page-size="page.per_page"
                                  @on-change="changePage"
                                  show-elevator></page>
                        </div>
                    </card>
                </tab-pane>
            </tabs>
        </div>
    </div>
</template>
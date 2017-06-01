<script>
    import injection from '../helpers/injection';

    export default {
        beforeRouteEnter(to, from, next) {
            next(() => {
                injection.sidebar.active('setting');
            });
        },
        data() {
            return {
                action: `${window.api}/mall/admin/upload`,
                alipayForm: {
                    enabled: true,
                    key: '',
                    number: '',
                },
                loading: false,
                unionPay: {
                    certificate: '',
                    enabled: true,
                    id: '',
                    key: '',
                },
                weChatForm: {
                    apply: '',
                    enabled: true,
                    key: '',
                    merchantId: '',
                    merchantKey: '',
                },
            };
        },
        methods: {
            removeLogo() {
                this.unionPay.certificate = '';
            },
            submit() {
                const self = this;
                self.loading = true;
                self.$refs.unionPay.validate(valid => {
                    if (valid) {
                        self.$Message.success('提交成功!');
                    } else {
                        self.loading = false;
                        self.$notice.error({
                            title: '请正确填写设置信息！',
                        });
                    }
                });
            },
            uploadBefore() {
                injection.loading.start();
            },
//            uploadError(error, data) {
//                const self = this;
//                injection.loading.error();
//                if (typeof data.message === 'object') {
//                    for (const p in data.message) {
//                        self.$notice.error({
//                            title: data.message[p],
//                        });
//                    }
//                } else {
//                    self.$notice.error({
//                        title: data.message,
//                    });
//                }
//            },
            uploadFormatError(file) {
                this.$notice.warning({
                    title: '文件格式不正确',
                    desc: `文件 ${file.name} 格式不正确`,
                });
            },
            uploadSuccess(data) {
                const self = this;
                injection.loading.finish();
                self.$notice.open({
                    title: data.message,
                });
                self.unionPay.certificate = data.data.path;
            },
        },
    };
</script>
<template>
    <div class="setting-wrap">
        <div class="pay-wrap">
            <tabs value="name1">
                <tab-pane label="支付宝" name="name1">
                    <card :bordered="false">
                        <div class="prompt-box">
                            <p>关于</p>
                            <p>支付宝，全球领先的独立第三方支付平台,致力于为广大用户提供安全快速的
                                电子支付/网上支付/安全支付/手机支付体验,及转账收款/水电煤缴费/信用卡还款/AA收款等生活..</p>
                        </div>
                        <i-form :label-width="200" :model="alipayForm" ref="alipayForm" :rules="alipayRules">
                            <row>
                                <i-col span="12">
                                    <form-item label="商户号" prop="number">
                                        <i-input v-model="alipayForm.number"></i-input>
                                        <a href="">点击此处获取</a>
                                    </form-item>
                                </i-col>
                            </row>
                            <row>
                                <i-col span="12">
                                    <form-item label="秘钥" prop="key">
                                        <i-input v-model="alipayForm.key"></i-input>
                                        <a href="">点击此处获取</a>
                                    </form-item>
                                </i-col>
                            </row>
                            <row>
                                <i-col span="12">
                                    <form-item label="是否开启">
                                        <i-switch size="large" v-model="alipayForm.enabled">
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
                    </card>
                </tab-pane>
                <tab-pane label="微信" name="name2">
                    <card :bordered="false">
                        <div class="prompt-box">
                            <p>关于</p>
                            <p> 微信支付是腾讯公司的支付业务品牌,微信支付提供公众号支付、APP支付、扫码支付、刷卡支付等支付方式。
                                微信支付结合微信公众账号,全面打通O2O生活消费领域,提供专.</p>
                        </div>
                        <i-form :label-width="200" :model="weChatForm" ref="weChatForm" :rules="weChatRules">
                            <row>
                                <i-col span="12">
                                    <form-item label="APP_ID" prop="apply">
                                        <i-input v-model="weChatForm.apply"></i-input>
                                        <p class="tip">
                                            应用ID ，在微信给公众平台“开发者中心”栏目可以查看到
                                        </p>
                                    </form-item>
                                </i-col>
                            </row>
                            <row>
                                <i-col span="12">
                                    <form-item label="APP_KEY" prop="key">
                                        <i-input v-model="weChatForm.key"></i-input>
                                        <p class="tip">
                                            在微信公众平台中“开发者中心”栏目可以产看到
                                        </p>
                                    </form-item>
                                </i-col>
                            </row>
                            <row>
                                <i-col span="12">
                                    <form-item label="商户ID">
                                        <i-input v-model="weChatForm.merchantId"></i-input>
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
                                        <i-input v-model="weChatForm.merchantKey"></i-input>
                                        <p class="tip">
                                            API密钥，在微信商户平台中“账户设置”-“账户安全”-“设置API密钥”
                                        </p>
                                    </form-item>
                                </i-col>
                            </row>
                            <row>
                                <i-col span="12">
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
                                                  @click.native="alipaySubmit">
                                            <span v-if="!loading">确认提交</span>
                                            <span v-else>正在提交…</span>
                                        </i-button>
                                    </form-item>
                                </i-col>
                            </row>
                        </i-form>
                    </card>
                </tab-pane>
                <tab-pane label="银联" name="name3">
                    <card :bordered="false">
                        <div class="prompt-box">
                            <p>关于</p>
                            <p>支付宝，全球领先的独立第三方支付平台,致力于为广大用户提供安全快速的
                                电子支付/网上支付/安全支付/手机支付体验,及转账收款/水电煤缴费/信用卡还款/AA收款等生活..</p>
                        </div>
                        <i-form :label-width="200" :model="unionPay" ref="unionPay" :rules="alipayRules">
                            <row>
                                <i-col span="12">
                                    <form-item label="ID" prop="id">
                                        <i-input v-model="unionPay.id"></i-input>
                                    </form-item>
                                </i-col>
                            </row>
                            <row>
                                <i-col span="12">
                                    <form-item label="证书" prop="certificate">
                                        <div class="image-preview" v-if="unionPay.certificate">
                                            <img :src="unionPay.certificate">
                                            <icon type="close" @click.native="removeLogo"></icon>
                                        </div>
                                        <upload :action="action"
                                                :before-upload="uploadBefore"
                                                :format="['jpg','jpeg','png']"
                                                :headers="{
                                                        Authorization: `Bearer ${$store.state.token.access_token}`
                                                    }"
                                                :max-size="2048"
                                                :on-error="uploadError"
                                                :on-format-error="uploadFormatError"
                                                :on-success="uploadSuccess"
                                                ref="upload"
                                                :show-upload-list="false"
                                                v-if="unionPay.certificate === '' || unionPay.certificate === null">
                                        </upload>
                                    </form-item>
                                </i-col>
                            </row>
                            <row>
                                <i-col span="12">
                                    <form-item label="秘钥" prop="key">
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
                    </card>
                </tab-pane>
            </tabs>
        </div>
    </div>
</template>
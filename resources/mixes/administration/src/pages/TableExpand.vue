<script>
    export default {
        props: {
            row: Object,
        },
    };
</script>
<template>
    <row class="expand-row">
        <i-col span="6">
            <span class="expand-key">付款方式：</span>
            <span class="expand-value">{{ row.payStyle }}</span>
        </i-col>
        <i-col span="6">
            <span class="expand-key">平台交易号：</span>
            <span class="expand-value">{{ row.transactionNum }}</span>
        </i-col>
        <i-col span="6">
            <span class="expand-key">交易内容：</span>
            <span class="expand-value">{{ row.transactionCtx }}</span>
        </i-col>
        <i-col span="6"></i-col>
    </row>
</template>
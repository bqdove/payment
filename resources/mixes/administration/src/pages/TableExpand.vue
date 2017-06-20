<script>
    export default {
        props: {
            row: Object,
        },
    };
</script>
<template>
    <row class="expand-row">
        <i-col>
            <span class="expand-key">付款方式：</span>
            <span class="expand-value">{{ row.payment }}</span>
        </i-col>
        <i-col>
            <span class="expand-key">平台交易号：</span>
            <span class="expand-value">{{ row.trade_no }}</span>
        </i-col>
        <i-col>
            <span class="expand-key">交易内容：</span>
            <span class="expand-value">{{ row.subject }}</span>
        </i-col>
    </row>
</template>
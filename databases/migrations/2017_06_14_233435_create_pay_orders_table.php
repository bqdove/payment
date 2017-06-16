<?php
/**
 * This file is part of Notadd.
 *
 * @datetime 2017-06-14 23:34:35
 */

use Illuminate\Database\Schema\Blueprint;
use Notadd\Foundation\Database\Migrations\Migration;

/**
 * Class CreatePayOrdersTable.
 */
class CreatePayOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('pay_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seller_id', 30)->comment('商户Id');
            //4位业务编码+4位年份+2位月份+2位日期+Id(6位左补零)+4位随机数
            $table->string('out_trade_no')->comment('商户定义订单号');
            $table->string('pay_no')->comment('支付单号');
            $table->string('trade_status')->comment('交易状态');//0-等待,1-成功,2-失败,3-退款中,4-退款完成
            $table->string('payment')->comment('支付方式');
            $table->string('subject',64)->comment('支付主题');
            $table->float('total_amount', 8,2)->comment('支付总额');
            $table->string('payment')->comment('付款方式');//wechat,alipay,unionpay
            $table->string('body')->comment('订单详情');
            $table->string('trader_no')->comment('各大支付平台提供的订单号');
            $table->string('pay_way')->comment('支付渠道');//微信扫码支付，支付宝网页支付。
            $table->json('options')->comment('各大平台差异化数据');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->drop('pay_orders');
    }
}

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
            $table->string('out_trade_no')->comment('商户定义订单号');
            $table->string('send_pay_date')->comment('支付时间');
            $table->string('total_amount')->comment('支付总额');
            $table->string('trade_status')->comment('订单状态');//trade_closed
            $table->string('buyer_logon_id')->comment('买家id');
            $table->string('payment')->comment('付款方式');//wechat,alipay,unionpay
            $table->string('msg')->comment('支付结果');//success fail
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

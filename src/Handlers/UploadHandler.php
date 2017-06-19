<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-04-24 18:42
 */
namespace Notadd\Multipay\Handlers;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class UploadHandler.
 */
class UploadHandler extends Handler
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * UploadHandler constructor.
     *
     * @param \Illuminate\Container\Container   $container
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     * @param \Notadd\Foundation\Setting\Contracts\SettingsRepository $settings

     */

    protected $settings;


    public function __construct(Container $container, Filesystem $filesystem, SettingsRepository $settings)
    {
        parent::__construct($container);
        $this->messages->push($this->translator->trans('上传文件成功！'));
        $this->files = $filesystem;
        $this->settings = $settings;
    }
    /**
     * Execute Handler.
     */
    public function execute()
    {
        $this->validate($this->request, [

            'file.file'    => '上传文件格式必须为文件格式！',
            'file.required' => '必须上传一个文件！',
        ]);


        $driver = $this->request->query('driver');//网关:微信 or 银联

        $certName = $this->request->query('certname');//证书类型 证书1, 证书2

        $avatar = $this->request->file($certName);

        $keyInSetting = $driver . "." . $certName;

        $hash = hash_file('md5', $avatar->getPathname(), false);

        $dictionary = $this->pathSplit($hash, '12', Collection::make([

            '../storage/uploads',
        ]))->implode(DIRECTORY_SEPARATOR);

        $file = Str::substr($hash, 12, 20) . '.' . $avatar->getClientOriginalExtension();

        if (!$this->files->exists($dictionary . DIRECTORY_SEPARATOR . $file)) {
            $avatar->move($dictionary, $file);
        }

        $this->data['path'] = $this->pathSplit($hash, '12,20', Collection::make([

                '../storage/uploads',
            ]))->implode('/') . '.' . $avatar->getClientOriginalExtension();

        $this->settings->set($keyInSetting,  $this->data['path']);

        return true;
    }
    /**
     * String split handler.
     *
     * @param string $path
     * @param string $dots
     * @param null   $data
     *
     * @return \Illuminate\Support\Collection|null
     */
    protected function pathSplit($path, $dots, $data = null)
    {
        $dots = explode(',', $dots);
        $data = $data ? $data : new Collection();
        $offset = 0;
        foreach ($dots as $dot) {
            $data->push(Str::substr($path, $offset, $dot));
            $offset += $dot;
        }
        return $data;
    }
}
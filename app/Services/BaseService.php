<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Abstract class BaseService — kelas dasar untuk semua service.
 *
 * Menerapkan requirement (h):
 * - Hak akses (public, protected, private)
 * - Properties
 * - Inheritance (kelas turunan harus extends class ini)
 *
 * @package App\Services
 */
abstract class BaseService
{
    /**
     * Model Eloquent yang dikelola oleh service ini.
     * Access modifier: protected — hanya bisa diakses oleh kelas turunan.
     *
     * @var Model
     */
    protected Model $model;

    /**
     * Nama service untuk keperluan logging.
     * Access modifier: private — hanya bisa diakses di dalam class ini sendiri.
     *
     * @var string
     */
    private string $serviceName;

    /**
     * Constructor — inisialisasi model dan nama service.
     *
     * @param Model  $model       Instance model Eloquent
     * @param string $serviceName Nama service untuk logging
     */
    public function __construct(Model $model, string $serviceName = 'BaseService')
    {
        $this->model = $model;
        $this->serviceName = $serviceName;
    }

    /**
     * Mendapatkan semua data (Req e: penggunaan method/fungsi).
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        $this->logAction('getAll');
        return $this->model->newQuery()->get();
    }

    /**
     * Mencari data berdasarkan ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function findById(int $id): ?Model
    {
        $this->logAction('findById', ['id' => $id]);
        return $this->model->newQuery()->find($id);
    }

    /**
     * Membuat data baru.
     *
     * @param array $data Array data (Req f: penggunaan array)
     * @return Model
     */
    public function create(array $data): Model
    {
        $this->logAction('create', $data);
        return $this->model->newQuery()->create($data);
    }

    /**
     * Mengupdate data berdasarkan ID.
     *
     * @param int   $id   ID record
     * @param array $data Array data baru
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $this->logAction('update', ['id' => $id]);
        $record = $this->findById($id);

        // Percabangan (Req d: if-else)
        if ($record === null) {
            Log::warning("{$this->serviceName}: Record not found", ['id' => $id]);
            return false;
        }

        return $record->update($data);
    }

    /**
     * Menghapus data berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $this->logAction('delete', ['id' => $id]);
        $record = $this->findById($id);

        if ($record === null) {
            return false;
        }

        return $record->delete();
    }

    /**
     * Method abstrak yang harus diimplementasikan oleh kelas turunan.
     * Menerapkan polymorphism (Req h): setiap service punya validasi berbeda.
     *
     * @param array $data Data yang akan divalidasi
     * @return array Array berisi aturan validasi
     */
    abstract protected function getValidationRules(array $data = []): array;

    /**
     * Method private untuk logging — hanya bisa diakses di class ini.
     * Mendemonstrasikan access modifier private (Req h).
     *
     * @param string $action Nama aksi
     * @param array  $context Data konteks tambahan
     * @return void
     */
    private function logAction(string $action, array $context = []): void
    {
        Log::info("{$this->serviceName}: {$action}", $context);
    }
}

<?php

namespace App\Repositories;

abstract class CacheRepository
{
  protected $repository;

  /**
   * @var \Illuminate\Database\Eloquent\Model
   */
  protected $model;

  /**
   * The default tag for caching.
   *
   * @var string
   */
  protected $tag;

  /**
   * The cache string modifier in case of any request differences.
   *
   * @var string
   */
  protected $modifier = '';

  /**
   * Find an instance of the model by its ID.
   *
   * @param int   $id
   * @param array $with
   *
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function fetch($id, $with = [])
  {
    $tags = array_merge([$this->tag], $with);
    $tag = "{$this->tag}.{$id}";
    try {
      return \Cache::tags($tags)->remember($tag, config('cache.time'), function () use (
        $id,
        $with
      ) {
        return $this->repository->fetch($id, $with);
      });
    } catch (\Exception $e) {
      \Log::warning('Error fetching cached resource', ['error' => $e, 'tag' => $tag]);
      return $this->repository->fetch($id, $with);
    }
  }

  /**
   * @param array $with Any relations to eager load
   *
   * @return mixed
   */
  public function getPaginated($with = [])
  {
    $page = \Request::get('page', 1);
    $tags = array_merge([$this->tag], $with);
    $tag = "{$this->tag}.paginated.page.{$page}{$this->modifier}";

    try {
      return \Cache::tags($tags)->remember($tag, config('cache.time'), function () use ($with) {
        return $this->repository->getPaginated($with);
      });
    } catch (\Exception $e) {
      \Log::warning('Error fetching cached resource', ['error' => $e, 'tag' => $tag]);
      return $this->repository->getPaginated($with);
    }
  }

  /**
   * Get all instances of the model.
   *
   * @param array $with Any relations to eager load
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function all($with = [])
  {
    $tags = array_merge([$this->tag], $with);
    $tag = "{$this->tag}.all{$this->modifier}";

    try {
      return \Cache::tags($tags)->remember($tag, config('cache.time'), function () use ($with) {
        return $this->repository->all($with);
      });
    } catch (\Exception $e) {
      \Log::warning('Error fetching cached resource', ['error' => $e, 'tag' => $tag]);
      return $this->repository->all($with);
    }
  }

  /**
   * Get a count of all models in the database.
   *
   * @return int
   */
  public function count()
  {
    $tag = "{$this->tag}.count{$this->modifier}";
    try {
      return \Cache::tags([$this->tag])->remember($tag, config('cache.time'), function () {
        return $this->repository->count();
      });
    } catch (\Exception $e) {
      \Log::warning('Error fetching cached resource', ['error' => $e, 'tag' => $tag]);
      return $this->repository->count();
    }
  }

  /**
   * Get an instance of a model by its slug.
   *
   * @param string $slug
   *
   * @return mixed
   */
  public function getBySlug($slug, $with = [])
  {
    $tags = array_merge([$this->tag], $with);
    $tag = "{$this->tag}.slug.{$slug}";
    try {
      return \Cache::tags($tags)->remember($tag, config('cache.time'), function () use (
        $slug,
        $with
      ) {
        return $this->repository->getBySlug($slug, $with);
      });
    } catch (\Exception $e) {
      \Log::warning('Error fetching cached resource', ['error' => $e, 'tag' => $tag]);
      return $this->repository->getBySlug($slug, $with);
    }
  }
}

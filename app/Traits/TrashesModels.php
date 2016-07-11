<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait TrashesModels
{
    /**
     * Remove the specified resource from storage.
     *
     * @param Model $model
     *
     * @throws \Exception
     *
     * @return Response
     *
     * @internal param int $id
     * @internal param Request $request
     */
    public function destroy(Model $model)
    {
        $model_name = $this->getModelName($model);

        $alert = "$model_name moved to trash";

        if ($model->trashed()) {
            $alert = "$model_name permanently deleted";
        }

        $this->delete($model);

        return redirect()->to($this->getIndexUrl($model))
            ->with(['alert' => $alert, 'alert-class' => 'success']);
    }

    /**
     * Delete a model, either softly or hard depending on whether it's already been soft-deleted
     *
     * @param  Model  $model
     *
     * @return Boolean
     */
    protected function delete(Model $model)
    {
        if ($model->trashed()) {
            return $model->forceDelete();
        }

        return $model->delete();
    }

    /**
     * Restore the model from soft-deletion.
     *
     * @param Model $model
     *
     * @throws \Exception
     */
    public function restore(Model $model)
    {
        $model->restore();

        $model_name = $this->getModelName($model);

        return redirect()->to($this->getIndexUrl($model))
            ->with([
                'alert'       => "$model_name Restored",
                'alert-class' => 'success',
                ]);
    }

    /**
     * Get the URL to the index page for the model.
     *
     * @return string
     */
    protected function getIndexUrl($model)
    {
      if (property_exists($this, 'index_url')) {
        return $this->index_url;
      }

      if (\Route::has("admin.{$model->table}.index")) {
        return route("admin.{$model->table}.index");
      }

      return route('admin.posts.index');
    }

    protected function getModelName($model)
    {
      return ucwords(class_basename($model));
    }
}

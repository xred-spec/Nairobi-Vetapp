<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductFilterRequest;
use Illuminate\Http\Request;
use App\Http\Requests\ProductoRequest;
use App\Models\Productos;
use App\Http\Resources\ProductoResource;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductoController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    public function index2(Request $request)
    {

        $this->authorize('viewAny', Productos::class);
        $productos = Productos::where('visibilidad', 'visible')->orderBy('nombre')->get();
        if ($productos->isEmpty()) {
            return $this->Respuesta(null, 'No se encontraron productos', 404, 'productos no encontrados');
        }
        return $this->Respuesta(ProductoResource::collection($productos), 'todos los productos', 200, null);
    }



    public function index(Request $request)
    {
        $this->authorize('viewAny', Productos::class);

        $productos = Productos::query()
            ->filter($request) 
            ->paginate(15);

        if ($productos->isEmpty()) {
            return $this->Respuesta(
                ['data' => [], 'paginacion' => ['current_page' => 1, 'last_page' => 1]],
                'No se encontraron productos con los criterios seleccionados',
                200
            );
        }

        return $this->Respuesta(
            [
                'data' => $productos->items(),
                'paginacion' => [
                    'current_page' => $productos->currentPage(),
                    'last_page' => $productos->lastPage(),
                    'per_page' => $productos->perPage(),
                    'total' => $productos->total(),
                ],
            ],
            'Productos obtenidos correctamente',
            200,
        );
    }

    public function search(ProductFilterRequest $request)
    {
        $this->authorize('viewAny', [Productos::class]);
        $productos = Productos::Filter($request)->paginate(15);
        if ($productos->isEmpty()) {
            return $this->Respuesta(null, 'No se encontraron productos con los criterios de búsqueda', 404, null);
        }
        return $this->Respuesta(ProductoResource::collection($productos), 'Productos encontrados', 200, null);
    }

    public function store(ProductoRequest $request)
    {
        $producto = Productos::where('marca', $request->marca)->where('nombre', $request->nombre)->first();
        if ($producto) {
            return $this->Respuesta(null, 'error al crear el producto', 409, 'el producto con ese nombre y marca ya existen');
        }

        $this->authorize('create', [Productos::class]);
        $producto = Productos::create($request->validated());
        return $this->Respuesta(new ProductoResource($producto), 'Producto creado correctamente', 201, null);
    }
    public function delete($id)
    {
        $producto = Productos::where('deleted_at', '=', null)->find($id);

        if (!$producto) {
            return $this->Respuesta(null, 'Producto no encontrado', 404, null);
        }

        $this->authorize('delete', $producto);

        $producto->delete();
        return $this->Respuesta($producto, 'Producto eliminado correctamente', 200, null);
    }
    /* public function store(ProductoRequest $request){
        $producto = Productos::where('marca',$request->marca)->where('nombre',$request->nombre)->first();
        if($producto){
            return $this->Respuesta(null,'error al crear el producto',409,'el producto con ese nombre y marca ya existen');
        }

        $this->authorize('create',[Productos::class]);
        $producto = Productos::create($request->validated());
        return $this->Respuesta(new ProductoResource($producto),'Producto creado correctamente',201,null);
    } */

    public function update(ProductoRequest $request, $id)
    {
        $producto = Productos::where('deleted_at', '=', null)->find($id);
        if (!$producto) {
            return $this->Respuesta(null, 'Producto no encontrado', 404, null);
        }
        $this->authorize('update', $producto);
        $producto->update($request->validated());

        return $this->Respuesta(new ProductoResource($producto), 'Producto actualizado correctamente', 200, null);
    }
}

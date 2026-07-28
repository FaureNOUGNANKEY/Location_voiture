<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PanneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'car_id'=> $this->car_id,
            'description'=>$this->description,
            'priority'=>$this->priority,
            'status'=>$this->status,
            'panneAmount'=>$this->panneAmount,

            'car' => new CarResource($this->whenLoaded('car')),

            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}

<?php
namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $this->merge(['user_id' => $this->user_id]);

        return [
            'user_id' => 'required|exists:users,id',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}

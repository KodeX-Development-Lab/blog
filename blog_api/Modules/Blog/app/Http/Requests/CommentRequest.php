<?php
namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $this->merge(['post_id' => $this->post_id]);

        return [
            'post_id'           => 'required|exists:posts,id',
            'parent_comment_id' => 'exists_or_null:comments,id',
            'content'           => 'required|string',
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

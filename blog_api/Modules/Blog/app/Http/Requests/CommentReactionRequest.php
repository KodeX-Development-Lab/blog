<?php
namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentReactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $this->merge(['post_id' => $this->post_id]);
        $this->merge(['comment_id' => $this->comment_id]);

        return [
            'post_id'          => 'required|exists:posts,id',
            'comment_id'       => 'required|exists:comments,id',
            'reaction_type_id' => 'required|exists:reaction_types,id',
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

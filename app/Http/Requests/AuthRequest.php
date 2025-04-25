<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả người dùng gửi yêu cầu (có thể chỉnh sửa nếu cần phân quyền)
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];

        // Nếu là yêu cầu đăng nhập, chỉ cần email và password
        if ($this->is('api/login')) {
            return [
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:6',
            ];
        }

        // Nếu là yêu cầu cập nhật, bỏ qua email của user hiện tại
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $this->user()->id;
            $rules['password'] = 'nullable|string|min:6|confirmed';
        }

        return $rules;
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}

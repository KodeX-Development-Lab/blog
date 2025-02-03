const api = import.meta.env.VITE_API;

function getToken() {
	return localStorage.getItem("token");
}

export async function fetchReactionTypes({ search }: { search: string }) {
	const token = getToken();

	const res = await fetch(`${api}/all-reaction-types?search=${search}`, {
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		return res.json();
	}

	throw new Error("Error: Check Network Log");
}

export async function fetchPosts({ search }: { search: string }) {
	const token = getToken();

	const res = await fetch(`${api}/all-posts?search=${search}`, {
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		const result = await res.json();
		return result?.data?.posts;
	}

	throw new Error("Error: Check Network Log");
}

export async function fetchUserPosts({ userId, search }: { userId: string | number, search: string }) {
	const token = getToken();

	const res = await fetch(`${api}/users/${userId}/all-posts?search=${search}`, {
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		return await res.json();
	}

	throw new Error("Error: Check Network Log");
}

export async function fetchPost(id: string) {
	const token = getToken();

	const res = await fetch(`${api}/posts/${id}`, {
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		const result = await res.json();
		return result?.data?.post;
	}

	throw new Error("Error: Check Network Log");
}

export async function fetchPostBriefReactions(id: string | number) {
	const token = getToken();

	const res = await fetch(`${api}/posts/${id}/brief-reactions`, {
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		return res.json();
	}

	throw new Error("Error: Check Network Log");
}

export async function fetchPostReactions(id: string | number, reactionTypeId?: string | number) {
	const token = getToken();

	const res = await fetch(`${api}/posts/${id}/all-reactions?reaction_type=${reactionTypeId}`, {
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		return res.json();
	}

	throw new Error("Error: Check Network Log");
}

export async function reactPost(postId: string, reactionTypeId: string) {
	const token = getToken();
	const res = await fetch(`${api}/posts/${postId}/reactions`, {
		method: "POST",
		body: JSON.stringify({ reaction_type_id: reactionTypeId }),
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		return res.json();
	}

	throw new Error("Error: Check Network Log");
}

export async function reactComment(postId: string | number, commentId: string, reactionTypeId: string) {
	const token = getToken();
	const res = await fetch(`${api}/posts/${postId}/comments/${commentId}/reactions`, {
		method: "POST",
		body: JSON.stringify({ reaction_type_id: reactionTypeId }),
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		return res.json();
	}

	throw new Error("Error: Check Network Log");
}

export async function fetchPostComments(postId: string) {
	const token = getToken();

	const res = await fetch(`${api}/posts/${postId}/all-comments`, {
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		const result = await res.json();
		return result?.data?.comments;
	}

	throw new Error("Error: Check Network Log");
}

export async function fetchCommentBriefReactions(postId: string | number, commentId: string | number) {
	const token = getToken();

	const res = await fetch(`${api}/posts/${postId}/comments/${commentId}/brief-reactions`, {
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		return res.json();
	}

	throw new Error("Error: Check Network Log");
}

export async function fetchCommentReactions(postId: string | number, commentId: string | number, reactionTypeId?: string | number) {
	const token = getToken();

	const res = await fetch(`${api}/posts/${postId}/comments/${commentId}/all-reactions?reaction_type=${reactionTypeId}`, {
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		return res.json();
	}

	throw new Error("Error: Check Network Log");
}

export async function postPost(content: string) {
	const token = getToken();
	const res = await fetch(`${api}/posts`, {
		method: "POST",
		body: JSON.stringify({ content }),
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		const result = await res.json();
		return result?.data?.post;
	}

	throw new Error("Error: Check Network Log");
}

export async function postComment(content: string, postId: string) {
	const token = getToken();
	const res = await fetch(`${api}/posts/${postId}/comments`, {
		method: "POST",
		body: JSON.stringify({ content }),
		headers: {
			"Content-Type": "application/json",
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		const result = await res.json();
		return result?.data?.comment;
	}

	throw new Error("Error: Check Network Log");
}

// export async function postUser(data) {
// 	const res = await fetch(`${api}/users`, {
// 		method: "POST",
// 		body: JSON.stringify(data),
// 		headers: {
// 			"Content-Type": "application/json",
// 		},
// 	});

// 	if (res.ok) {
// 		return res.json();
// 	}

// 	throw new Error("Error: Check Network Log");
// }

export async function postLogin(email: string, password: string) {
	const res = await fetch(`${api}/auth/login`, {
		method: "POST",
		body: JSON.stringify({ email, password }),
		headers: {
			"Content-Type": "application/json",
		},
	});

	if (res.ok) {
		return res.json();
	}

	throw new Error("Incorrect username or password");
}

export async function fetchVerify() {
	const token = getToken();
	const res = await fetch(`${api}/auth/user`, {
		headers: {
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		return res.json();
	}

	return false;
}

export async function fetchUser(id: string) {
	const token = getToken();
	const res = await fetch(`${api}/users/${id}`, {
		headers: {
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		return res.json();
	}

	return false;
}

export async function deletePost(postId: string) {
	const token = getToken();
	const res = await fetch(`${api}/posts/${postId}`, {
		method: "DELETE",
		headers: {
			Authorization: `Bearer ${token}`,
		},
	});

	return res.text();
}

export async function deleteComment(postId: string, commentId: string) {
	const token = getToken();
	const res = await fetch(`${api}/posts/${postId}/comments/${commentId}`, {
		method: "DELETE",
		headers: {
			Authorization: `Bearer ${token}`,
		},
	});

	if (res.ok) {
		return res.text();
	}

	throw new Error("Error: Check Network Log");
}

export async function postFollow(id: number | string) {
	const token = getToken();
	const res = await fetch(`${api}/users/${id}/follow`, {
		method: "POST",
		headers: {
			Authorization: `Bearer ${token}`,
		},
	});

	return res.json();
}
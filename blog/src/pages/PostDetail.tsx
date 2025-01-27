import { Box, Card, CardContent, Typography, IconButton, Alert } from "@mui/material";

import {
	Alarm as TimeIcon,
	AccountCircle as UserIcon,
	Delete as DeleteIcon,
} from "@mui/icons-material";

import { useNavigate, useParams } from "react-router-dom";

import { green } from "@mui/material/colors";

import { formatRelative } from "date-fns";
import { PostType } from "../types/types";
import { useMutation, useQuery } from "react-query";
import { fetchPost } from "../libs/fetcher";
import Post from "../components/Post";
import { queryClient, useApp } from "../ThemedApp";
import Comments from "../components/Comments";

export default function PostDetail() {
	const { setGlobalMsg } = useApp();
	const navigate = useNavigate();
	const { id } = useParams();

	const { isLoading, isError, error, data } = useQuery(["post", id], () => fetchPost(id));

	const remove = useMutation(async id => deletePost(id), {
		onMutate: async id => {
			await queryClient.cancelQueries("posts");
			await queryClient.setQueryData("posts", old =>
				old.filter(item => item.id !== id)
			);

			setGlobalMsg("A post deleted");
		},
	});

	if (isError) {
		return (
			<Box>
				<Alert severity="warning">{error.message}</Alert>
			</Box>
		);
	}

	if (isLoading) {
		return <Box sx={{ textAlign: "center" }}>Loading...</Box>;
	}

	return (
		<>
			<Post
				key={data.id}
				post={data}
				remove={remove.mutate}
			/>
			<h5>Comments</h5>
			<Comments/>
		</>
	);
}
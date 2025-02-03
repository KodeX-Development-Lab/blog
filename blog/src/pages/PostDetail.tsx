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
import { fetchPost, fetchReactionTypes } from "../libs/fetcher";
import Post from "../components/Post";
import { queryClient, useApp } from "../ThemedApp";
import Comments from "../components/Comments";
import { useEffect } from "react";

export default function PostDetail() {
	const { setGlobalMsg, setReactionTypes, setDefaultReactionType } = useApp();
	const navigate = useNavigate();
	const { id } = useParams();

	const { isLoading, isError, error, data } = useQuery(["post", id], () => fetchPost(id));
	const { data: reactionTypesResult } = useQuery("reactionTypes", () => fetchReactionTypes({ search: "" }));


	useEffect(() => {
		if (reactionTypesResult?.data?.reactionTypes && reactionTypesResult?.data?.reactionTypes.length > 0) {
			const reactionTypes = reactionTypesResult?.data?.reactionTypes;
			setReactionTypes(reactionTypes);

			const reactionTypesCount = reactionTypes.length;

			for (let i = 0; i < reactionTypesCount; i++) {
				const element = reactionTypes[i];

				if (element.is_default) {
					setDefaultReactionType(reactionTypes[i]);
					break;
				}
			}

			// if (defaultReactionType == null) {
			//   console.log("no")
			//   setDefaultReactionType(reactionTypes[0]);
			// }
		}
	}, [reactionTypesResult]);

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
			<Comments />
		</>
	);
}
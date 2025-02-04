import { Box, Card, CardContent, Typography, IconButton, ButtonGroup, Button } from "@mui/material";

import {
	Alarm as TimeIcon,
	AccountCircle as UserIcon,
	Delete as DeleteIcon,
	ChatBubbleOutline as CommentIcon
} from "@mui/icons-material";

import { Link, useNavigate } from "react-router-dom";

import { green, purple } from "@mui/material/colors";

import { formatRelative } from "date-fns";
import { PostType } from "../types/types";
import ReactionButton from "./ReactionButton";
import { reactPost } from "../libs/fetcher";
import { useMutation } from "react-query";
import { queryClient, useApp } from "../ThemedApp";

interface PostProps {
	post: PostType,
	remove: (id: number) => void,
	primary: boolean,
}

export default function Post({ post, remove, primary }: PostProps) {
	const navigate = useNavigate();

	const doReaction = useMutation(async (reactionTypeId) => reactPost(post.id, reactionTypeId), {
		onSuccess: async (result) => {
			const post = result.data?.post;
			await queryClient.cancelQueries("posts");
			await queryClient.cancelQueries("userPosts");

			await queryClient.setQueryData(["posts", true], old => {
				return old.map(item => {
					if(item.id == post.id) {
						return post;
					}

					return item;
				})
			});
			await queryClient.setQueryData("userPosts", old => {
				return old.map(item => {
					if(item.id == post.id) {
						return post;
					}

					return item;
				})
			});
		},
	});

	return (
		<Card sx={{ mb: 2 }}>
			{primary && <Box sx={{ height: 50, bgcolor: green[500] }} />}

			<CardContent
				onClick={() => {
					navigate(`/posts/${post.id}`);
				}}
				sx={{ cursor: "pointer" }}>
				<Box
					sx={{
						display: "flex",
						flexDirection: "row",
						justifyContent: "space-between",
					}}>
					<Box
						sx={{
							display: "flex",
							flexDirection: "row",
							alignItems: "center",
							gap: 1,
						}}>
						<TimeIcon
							fontSize="10"
							color="success"
						/>
						<Typography
							variant="caption"
							sx={{ color: green[500] }}>
							{formatRelative(post.createdAt, new Date())}
						</Typography>
					</Box>
					{
						post.canDelete &&
						<IconButton
							sx={{ color: "text.fade" }}
							size="small"
							onClick={e => {
								remove(post.id);
								e.stopPropagation();
							}}>
							<DeleteIcon
								color="inherit"
								fontSize="inherit"
							/>
						</IconButton>
					}
				</Box>

				<Typography sx={{ my: 3 }}>{post.content}</Typography>
				<Box
					onClick={(e) => {
						navigate(`/users/${post.user.id}`)
						e.stopPropagation();
					}}
					sx={{
						display: "flex",
						flexDirection: "row",
						alignItems: "center",
						gap: 1,
					}}>

					<UserIcon
						fontSize="12"
						color="info"
					/>
					<Typography variant="caption">{post.user.name}</Typography>
				</Box>

				<Box
					sx={{
						display: "flex",
						flexDirection: "row",
						justifyContent: "space-between",
					}}>
					<ReactionButton
						type='post'
						post={post}
						myReaction={post.myReaction}
						reactionBrief={post.reactionBrief}
						reactionsCount={post.reactionsCount}
						doReaction={doReaction.mutate}
					/>
					<ButtonGroup sx={{ ml: 3 }}>
						<IconButton size="small">
							<CommentIcon
								fontSize="small"
								color="info"
							/>
						</IconButton>
						<Button
							sx={{ color: "text.fade" }}
							variant="text"
							size="small">
							{post.commentsCount}
						</Button>
					</ButtonGroup>
				</Box>
			</CardContent>
		</Card >
	);
}
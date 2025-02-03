import { Button } from "@mui/material";
import { useMutation } from "react-query";

import { useApp, queryClient } from "../ThemedApp";
import { postFollow } from "../libs/fetcher";

export default function FollowButton({ user }: { user: Object }) {
    const { auth } = useApp();

    const follow = useMutation(
        id => {
            return postFollow(id);
        },
        {
            onSuccess: async () => {
                await queryClient.refetchQueries("user");
            },
        }
    );

    return auth.id === user.id ? (
        <></>
    ) : (
        <Button
            size="small"
            edge="end"
            variant={user.alreadyFollowed ? "outlined" : "contained"}
            sx={{ borderRadius: 5 }}
            onClick={e => {
                follow.mutate(user.id);
                e.stopPropagation();
            }}>
            {user.alreadyFollowed ? "Following" : "Follow"}
        </Button>
    );
}
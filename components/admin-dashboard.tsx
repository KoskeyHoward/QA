"use client"

import { useState, useEffect } from "react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { useToast } from "@/hooks/use-toast"
import { Star, Trash2, Check, X, LogOut } from "lucide-react"
import { useRouter } from "next/navigation"
import type { Feedback } from "@/lib/database"

export function AdminDashboard() {
  const [feedbacks, setFeedbacks] = useState<Feedback[]>([])
  const [isLoading, setIsLoading] = useState(true)
  const [activeTab, setActiveTab] = useState("all")
  const { toast } = useToast()
  const router = useRouter()

  useEffect(() => {
    fetchFeedbacks()
  }, [])

  async function fetchFeedbacks() {
    try {
      const response = await fetch("/api/admin/feedbacks")
      if (response.ok) {
        const data = await response.json()
        setFeedbacks(data)
      } else {
        toast({
          title: "Error",
          description: "Failed to fetch feedbacks",
          variant: "destructive",
        })
      }
    } catch (error) {
      console.error("Failed to fetch feedbacks:", error)
      toast({
        title: "Error",
        description: "Failed to fetch feedbacks",
        variant: "destructive",
      })
    } finally {
      setIsLoading(false)
    }
  }

  async function handleApprove(id: number, approved: boolean) {
    try {
      const response = await fetch(`/api/feedback/${id}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ approved }),
      })

      if (response.ok) {
        toast({
          title: "Success!",
          description: `Feedback ${approved ? "approved" : "disapproved"} successfully.`,
        })
        // Update the local state immediately for better UX
        setFeedbacks((prev) => prev.map((feedback) => (feedback.id === id ? { ...feedback, approved } : feedback)))
      } else {
        const error = await response.json()
        toast({
          title: "Error",
          description: error.error || "Failed to update feedback.",
          variant: "destructive",
        })
      }
    } catch (error) {
      console.error("Error updating feedback:", error)
      toast({
        title: "Error",
        description: "Something went wrong. Please try again.",
        variant: "destructive",
      })
    }
  }

  async function handleDelete(id: number) {
    if (!confirm("Are you sure you want to delete this feedback?")) {
      return
    }

    try {
      const response = await fetch(`/api/feedback/${id}`, {
        method: "DELETE",
      })

      if (response.ok) {
        toast({
          title: "Success!",
          description: "Feedback deleted successfully.",
        })
        // Update the local state immediately for better UX
        setFeedbacks((prev) => prev.filter((feedback) => feedback.id !== id))
      } else {
        const error = await response.json()
        toast({
          title: "Error",
          description: error.error || "Failed to delete feedback.",
          variant: "destructive",
        })
      }
    } catch (error) {
      console.error("Error deleting feedback:", error)
      toast({
        title: "Error",
        description: "Something went wrong. Please try again.",
        variant: "destructive",
      })
    }
  }

  async function handleLogout() {
    try {
      // Clear the session cookie
      document.cookie = "admin-session=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
      router.push("/")
    } catch (error) {
      console.error("Logout failed:", error)
    }
  }

  // Filter feedbacks based on status
  const allFeedbacks = feedbacks
  const approvedFeedbacks = feedbacks.filter((feedback) => feedback.approved === true)
  const rejectedFeedbacks = feedbacks.filter((feedback) => feedback.approved === false && feedback.id !== undefined)
  const pendingFeedbacks = feedbacks.filter((feedback) => feedback.approved === false)

  // Get counts for badges
  const counts = {
    all: allFeedbacks.length,
    approved: approvedFeedbacks.length,
    rejected: rejectedFeedbacks.length,
    pending: pendingFeedbacks.length,
  }

  function renderFeedbackCard(feedback: Feedback, showApproveButton = false, showDisapproveButton = false) {
    return (
      <Card key={feedback.id}>
        <CardHeader>
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-4">
              <CardTitle className="text-lg">{feedback.name}</CardTitle>
              <Badge variant={feedback.approved ? "default" : "secondary"}>
                {feedback.approved ? "Approved" : "Pending"}
              </Badge>
              {feedback.rating && (
                <div className="flex items-center gap-1">
                  {Array.from({ length: feedback.rating }).map((_, i) => (
                    <Star key={i} className="w-4 h-4 fill-yellow-400 text-yellow-400" />
                  ))}
                </div>
              )}
            </div>
            <div className="flex items-center gap-2">
              {showApproveButton && (
                <Button
                  size="sm"
                  onClick={() => handleApprove(feedback.id, true)}
                  className="bg-green-600 hover:bg-green-700"
                >
                  <Check className="w-4 h-4 mr-1" />
                  Approve
                </Button>
              )}
              {showDisapproveButton && (
                <Button size="sm" variant="outline" onClick={() => handleApprove(feedback.id, false)}>
                  <X className="w-4 h-4 mr-1" />
                  Disapprove
                </Button>
              )}
              <Button size="sm" variant="destructive" onClick={() => handleDelete(feedback.id)}>
                <Trash2 className="w-4 h-4 mr-1" />
                Delete
              </Button>
            </div>
          </div>
          <div className="flex items-center gap-4 text-sm text-gray-600">
            <span>{feedback.email}</span>
            <span>{new Date(feedback.created_at).toLocaleDateString()}</span>
          </div>
        </CardHeader>
        <CardContent>
          <p className="text-gray-700">{feedback.message}</p>
        </CardContent>
      </Card>
    )
  }

  function renderFeedbackList(
    feedbackList: Feedback[],
    emptyMessage: string,
    showApproveButton = false,
    showDisapproveButton = false,
  ) {
    if (feedbackList.length === 0) {
      return (
        <div className="text-center py-12">
          <p className="text-gray-500 text-lg">{emptyMessage}</p>
        </div>
      )
    }

    return (
      <div className="grid gap-6">
        {feedbackList.map((feedback) => renderFeedbackCard(feedback, showApproveButton, showDisapproveButton))}
      </div>
    )
  }

  if (isLoading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <p>Loading...</p>
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="container mx-auto px-4 py-8">
        <div className="flex justify-between items-center mb-8">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p className="text-gray-600">Manage customer feedback</p>
          </div>
          <Button variant="outline" onClick={handleLogout}>
            <LogOut className="w-4 h-4 mr-2" />
            Logout
          </Button>
        </div>

        <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
          <TabsList className="grid w-full grid-cols-4">
            <TabsTrigger value="all" className="flex items-center gap-2">
              All
              {counts.all > 0 && (
                <Badge variant="secondary" className="ml-1 px-2 py-0 text-xs">
                  {counts.all}
                </Badge>
              )}
            </TabsTrigger>
            <TabsTrigger value="approved" className="flex items-center gap-2">
              Approved
              {counts.approved > 0 && (
                <Badge variant="default" className="ml-1 px-2 py-0 text-xs">
                  {counts.approved}
                </Badge>
              )}
            </TabsTrigger>
            <TabsTrigger value="rejected" className="flex items-center gap-2">
              Rejected
              {counts.rejected > 0 && (
                <Badge variant="destructive" className="ml-1 px-2 py-0 text-xs">
                  {counts.rejected}
                </Badge>
              )}
            </TabsTrigger>
            <TabsTrigger value="pending" className="flex items-center gap-2">
              Pending
              {counts.pending > 0 && (
                <Badge variant="outline" className="ml-1 px-2 py-0 text-xs">
                  {counts.pending}
                </Badge>
              )}
            </TabsTrigger>
          </TabsList>

          <TabsContent value="all" className="mt-6">
            <div className="mb-4">
              <h2 className="text-xl font-semibold text-gray-900">All Feedback</h2>
              <p className="text-gray-600">View and manage all feedback submissions</p>
            </div>
            {renderFeedbackList(allFeedbacks, "No feedback available.")}
          </TabsContent>

          <TabsContent value="approved" className="mt-6">
            <div className="mb-4">
              <h2 className="text-xl font-semibold text-gray-900">Approved Feedback</h2>
              <p className="text-gray-600">Feedback that is visible to the public</p>
            </div>
            {renderFeedbackList(approvedFeedbacks, "No approved feedback yet.", false, true)}
          </TabsContent>

          <TabsContent value="rejected" className="mt-6">
            <div className="mb-4">
              <h2 className="text-xl font-semibold text-gray-900">Rejected Feedback</h2>
              <p className="text-gray-600">Feedback that has been disapproved</p>
            </div>
            {renderFeedbackList(rejectedFeedbacks, "No rejected feedback.", true, false)}
          </TabsContent>

          <TabsContent value="pending" className="mt-6">
            <div className="mb-4">
              <h2 className="text-xl font-semibold text-gray-900">Pending Feedback</h2>
              <p className="text-gray-600">New feedback awaiting approval</p>
            </div>
            {renderFeedbackList(pendingFeedbacks, "No pending feedback.", true, false)}
          </TabsContent>
        </Tabs>
      </div>
    </div>
  )
}

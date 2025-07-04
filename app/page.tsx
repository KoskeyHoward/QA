"use client"

import { useState, useEffect } from "react"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Star } from "lucide-react"
import Link from "next/link"
import { Button } from "@/components/ui/button"

interface Feedback {
  id: number
  name: string
  email: string
  message: string
  rating?: number
  approved: boolean
  created_at: string
}

export default function HomePage() {
  const [approvedFeedbacks, setApprovedFeedbacks] = useState<Feedback[]>([])
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    fetchApprovedFeedbacks()
  }, [])

  async function fetchApprovedFeedbacks() {
    try {
      const response = await fetch("/api/feedbacks")
      if (response.ok) {
        const data = await response.json()
        setApprovedFeedbacks(data)
      }
    } catch (error) {
      console.error("Failed to fetch feedbacks:", error)
    } finally {
      setIsLoading(false)
    }
  }

  if (isLoading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <p className="text-lg">Loading feedback...</p>
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="container mx-auto px-4 py-8">
        <div className="text-center mb-8">
          <h1 className="text-4xl font-bold text-gray-900 mb-4">Customer Feedback</h1>
          <p className="text-lg text-gray-600 mb-6">See what our customers are saying about us</p>
          <div className="flex gap-4 justify-center">
            <Button asChild>
              <Link href="/submit-feedback">Submit Feedback</Link>
            </Button>
            <Button variant="outline" asChild>
              <Link href="/admin/login">Admin Login</Link>
            </Button>
          </div>
        </div>

        {approvedFeedbacks.length === 0 ? (
          <div className="text-center py-12">
            <p className="text-gray-500 text-lg">No approved feedback available yet.</p>
            <p className="text-gray-400 text-sm mt-2">
              Submit some feedback or ask an admin to approve existing feedback!
            </p>
          </div>
        ) : (
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            {approvedFeedbacks.map((feedback) => (
              <Card key={feedback.id} className="h-full">
                <CardHeader>
                  <div className="flex items-center justify-between">
                    <CardTitle className="text-lg">{feedback.name}</CardTitle>
                    {feedback.rating && (
                      <div className="flex items-center gap-1">
                        {Array.from({ length: feedback.rating }).map((_, i) => (
                          <Star key={i} className="w-4 h-4 fill-yellow-400 text-yellow-400" />
                        ))}
                      </div>
                    )}
                  </div>
                  <Badge variant="secondary" className="w-fit">
                    {feedback.email}
                  </Badge>
                </CardHeader>
                <CardContent>
                  <p className="text-gray-700 mb-4">{feedback.message}</p>
                  <p className="text-sm text-gray-500">{new Date(feedback.created_at).toLocaleDateString()}</p>
                </CardContent>
              </Card>
            ))}
          </div>
        )}
      </div>
    </div>
  )
}
